<?php

namespace App\Livewire\Locality;

use App\Models\Locality;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class LocalityTable extends PowerGridComponent
{
    use WithExport;
    public ?int $cityId = null;
    public array $name;

    public string $tableName = 'LocalityTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make(fileName: 'semtler')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSoftDeletes()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(perPage: 50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Locality::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('city_id', function ($role) {
                return $role->city->name ?? "---";
            })
            ->add('district_id', function ($role) {
                return $role->district->name ?? "---";
            })
            ->add('neighborhood_id', function ($role) {
                return $role->neighborhood->name ?? "---";
            })
            ->add('name')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('İl Adı', 'city_id'),
            Column::make('İlçe Adı', 'district_id'),
            Column::make('Mahalle Adı', 'neighborhood_id'),
            Column::make('Semt Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update localities'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update localities'),
                    'Aktif',
                    'Pasif',
                ),

            Column::make('OLUŞTURULMA TARİHİ', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('EYLEMLER')
                ->visibleInExport(visible: false),
        ];
    }

    public function filters(): array
    {
        // return [];
        $city_id = $this->filters['select']['city_id'] ?? null;
        $query = Locality::query();
        if ($city_id > 0) {
            $query->where('city_id', $city_id)->orderBy('city_id', 'asc');
        }
        $district_id = $this->filters['select']['district_id'] ?? null;
        if ($district_id > 0) {
            $query->where('district_id', $district_id)->orderBy('district_id', 'asc');
        }
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('city_id')
                ->dataSource(City::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('district_id')
                ->depends(['city_id'])
                ->dataSource(
                    fn($depends) => District::query()
                        ->when(
                            isset($depends['city_id']),
                            fn(Builder $query) => $query->whereRelation(
                                'city',
                                fn(Builder $builder) => $builder->where('id', $depends['city_id'])
                            )
                        )
                        ->get()
                )
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(Locality $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('localities.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-locality', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update localities') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete localities') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Locality::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
        $this->skipRender();
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'unique:localities'
            ],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name'     => 'Mahalle Adı',
        ];
    }

    protected function messages()
    {
        return [
            'name.required'     => 'Lütfen mahalle adını yazınız.',
            'name.unique'       => ':value , Bu mahalle adı zaten kullanılmaktadır.',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $this->withValidator(function (\Illuminate\Validation\Validator $validator) use ($id, $field) {
            if ($validator->errors()->isNotEmpty()) {
                $this->dispatch('toggle-' . $field . '-' . $id);
            }
        })->validate();

        Locality::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
