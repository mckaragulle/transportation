<?php

namespace App\Livewire\District\Landlord;

use App\Models\Landlord\LandlordCity;
use App\Models\Landlord\LandlordDistrict;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class DistrictTable extends PowerGridComponent
{
    use WithExport;
    public ?int $cityId = null;

    public string $tableName = 'DistrictTable';

    public array $name;

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            PowerGrid::exportable(fileName: 'ilceler')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSoftDeletes()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage(perPage: 50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return LandlordDistrict::query();
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
            Column::make('İlçe Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update districts'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update districts'),
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
        $id = $this->filters['select']['city_id'] ?? null;
        $query = LandlordDistrict::query();
        if ($id > 0) {
            $query->where('city_id', $id)->orderBy('city_id', 'asc');
        }
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('city_id')
                ->dataSource(LandlordCity::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(LandlordDistrict $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('districts.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-district', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update districts') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete districts') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        LandlordDistrict::query()->find($id)->update([
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
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name'     => 'İlçe Adı',
        ];
    }

    protected function messages()
    {
        return [
            'name.required'     => 'Lütfen ilçe adını yazınız.',
            'name.unique'     => ':value , Bu ilçe adı zaten kullanılmaktadır.',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $this->withValidator(function (\Illuminate\Validation\Validator $validator) use ($id, $field) {
            if ($validator->errors()->isNotEmpty()) {
                $this->dispatch('toggle-' . $field . '-' . $id);
            }
        })->validate();

        LandlordDistrict::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
