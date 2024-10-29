<?php

namespace App\Livewire\Neighborhood;

use App\Models\Neighborhood;
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

final class NeighborhoodTable extends PowerGridComponent
{
    use WithExport;
    public ?int $cityId = null;
    public array $postcode;
    public array $name;

    public string $tableName = 'NeighborhoodTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make(fileName: 'mahalleler')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(perPage: 50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Neighborhood::query();
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
            Column::make('Mahalle Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update neighborhoods'),
                    fallback: '- empty -'
                ),
            Column::make('Posta Kodu', 'postcode')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update neighborhoods'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update neighborhoods'),
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
        $city_id = $this->filters['select']['city_id'] ?? null;
        $query = Neighborhood::query();
        if ($city_id > 0) {
            $query->where('city_id', $city_id)->orderBy('city_id', 'asc');
        }
        $district_id = $this->filters['select']['district_id'] ?? null;
        if ($district_id > 0) {
            $query->where('district_id', $district_id)->orderBy('district_id', 'asc');
        }
        return [
            Filter::select('city_id')
                ->dataSource(City::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('district_id')
                ->dataSource(District::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(Neighborhood $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('neighborhoods.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-neighborhood', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update neighborhoods') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete neighborhoods') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Neighborhood::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
        $this->skipRender();
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'unique:neighborhoods'
            ],
 
            'postcode' => [
                'required',
                'unique:neighborhoods'
            ],
        ];
    }
 
    protected function validationAttributes()
    {
        return [
            'name'     => 'Mahalle Adı',
            'postcode' => 'Mahalle Posta Kodu',
        ];
    }
 
    protected function messages()
    {
        return [
            'name.required'     => 'Lütfen mahalle adını yazınız.',
            'name.unique'     => ':value , Bu mahalle adı zaten kullanılmaktadır.',
            'postcode.required'     => 'Lütfen posta kodunu yazınız.',
            'postcode.unique'     => ':value , Bu posta kodu zaten kullanılmaktadır.',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $this->withValidator(function (\Illuminate\Validation\Validator $validator) use ($id, $field) {
            if ($validator->errors()->isNotEmpty()) {
                $this->dispatch('toggle-'.$field.'-'.$id);
            }
        })->validate();

        Neighborhood::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
