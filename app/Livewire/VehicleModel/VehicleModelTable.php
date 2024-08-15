<?php

namespace App\Livewire\VehicleModel;

use App\Models\VehicleModel;
use App\Models\VehicleBrand;
use App\Models\VehicleTicket;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class VehicleModelTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'VehicleModelTable';

    public bool $showFilters = true;

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make('marka-modelleri')
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
        return VehicleModel::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('vehicle_brand_id', function ($role) {
                return $role->vehicle_brand->name ?? "---";
            })
            ->add('vehicle_ticket_id', function ($role) {
                return $role->vehicle_ticket->name ?? "---";
            })
            ->add('name')
            ->add('insurance_number')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Araç Markaları', 'vehicle_brand_id'),
            Column::make('Araç Tipleri', 'vehicle_ticket_id'),
            Column::make('Model Yılı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update vehicleModels'),
                    fallback: '- empty -'
                ),
            Column::make('Kasko Kodu', 'insurance_number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update vehicleModels'),
                    fallback: '- empty -'
                ),
            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update vehicleModels'),
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
        $id = $this->filters['select']['vehicle_brand_id']??null;
        $query = VehicleTicket::query();
        if($id > 0)
        {
            $query->where('vehicle_brand_id', $id);
        }
        return [
            Filter::select('vehicle_brand_id')
                ->dataSource(VehicleBrand::all())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('vehicle_ticket_id')
                ->dataSource($query->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(VehicleModel $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('vehicleModels.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-vehicleModel', ['id' => $row->id]),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        VehicleModel::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        VehicleModel::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}