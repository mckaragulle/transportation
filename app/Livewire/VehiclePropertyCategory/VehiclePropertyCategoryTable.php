<?php

namespace App\Livewire\VehiclePropertyCategory;

use App\Models\VehiclePropertyCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

final class VehiclePropertyCategoryTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'VehiclePropertyCategoryTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make(fileName: 'arac-ozellik-kategorileri')
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
        return VehiclePropertyCategory::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
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

            Column::make('Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update vehiclePropertyCategories'),
                    fallback: '- empty -'
                ),

            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update vehiclePropertyCategories'),
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
        return [];
    }

    public function actions(VehiclePropertyCategory $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('vehiclePropertyCategories.edit', ['id' => $row->id])
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
                ->when(fn ($row) => auth()->user()->can('update vehiclePropertyCategories') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn ($row) => auth()->user()->can('delete vehiclePropertyCategories') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        VehiclePropertyCategory::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        VehiclePropertyCategory::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
