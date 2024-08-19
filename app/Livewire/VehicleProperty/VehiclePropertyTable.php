<?php

namespace App\Livewire\VehicleProperty;

use App\Models\VehicleProperty;
use App\Models\VehiclePropertyCategory;
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

final class VehiclePropertyTable extends PowerGridComponent
{
    use WithExport;
    public ?int $vehiclePropertyCategoryId = null;

    public string $tableName = 'VehiclePropertyTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make(fileName: 'arac-ozellikleri')
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
        return VehicleProperty::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('vehicle_property_category_id', function ($role) {
                return $role->vehicle_property_category->name ?? "---";
            })
            ->add('vehicle_property_id', function ($role) {
                return $role->vehicle_property->name ?? "---";
            })
            ->add('name')
            ->add('slug')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Özellik Kategorisi', 'vehicle_property_category_id'),
            Column::make('Özellik Grubu', 'vehicle_property_id'),
            Column::make('Özellik', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update vehicle_properties'),
                    fallback: '- empty -'
                ),

            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update vehicle_properties'),
                    'Aktif',
                    'Pasif',
                ),

            Column::make('Oluşturulma Tarihi', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Eylemler')
                ->visibleInExport(visible: false),
        ];
    }

    public function filters(): array
    {
        $id = $this->filters['select']['vehicle_property_category_id']??null;
        $query = VehicleProperty::query();
        if($id > 0)
        {
            $query->where('vehicle_property_category_id', $id)->whereNull('vehicle_property_id');
        }
        return [
            Filter::select('vehicle_property_category_id')
                ->dataSource(VehiclePropertyCategory::all())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('vehicle_property_id')
                ->dataSource($query->get())
                ->optionLabel('name')
                ->optionValue('id'),
            
        ];
    }

    public function actions(VehicleProperty $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('vehicle_properties.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-vehiclePropertyCategory', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn ($row) => auth()->user()->can('update vehicle_properties') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn ($row) => auth()->user()->can('delete vehicle_properties') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        VehicleProperty::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        VehicleProperty::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
