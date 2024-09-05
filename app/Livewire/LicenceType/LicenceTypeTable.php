<?php

namespace App\Livewire\LicenceType;

use App\Models\LicenceType;
use App\Models\LicenceTypeCategory;
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

final class LicenceTypeTable extends PowerGridComponent
{
    use WithExport;
    public ?int $licenceTypeCategoryId = null;

    public string $tableName = 'LicenceTypeTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            Exportable::make(fileName: 'surucu-belgesi-tipleri')
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
        return LicenceType::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('licence_type_category_id', function ($role) {
                return $role->licence_type_category->name ?? "---";
            })
            ->add('licence_type_id', function ($role) {
                return $role->licence_type->name ?? "---";
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

            Column::make('Sürücü Belgesi Kategorisi', 'licence_type_category_id'),
            Column::make('Sürücü Belgesi Grubu', 'licence_type_id'),
            Column::make('Sürücü Belgesi', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update licence_types'),
                    fallback: '- empty -'
                ),

            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update licence_types'),
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
        $id = $this->filters['select']['licence_type_category_id']??null;
        $query = LicenceType::query();
        if($id > 0)
        {
            $query->where('licence_type_category_id', $id)->whereNull('licence_type_id')->orderBy('licence_type_category_id', 'asc');
        }
        return [
            Filter::select('licence_type_category_id')
                ->dataSource(LicenceTypeCategory::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('licence_type_id')
                ->dataSource($query->get())
                ->optionLabel('name')
                ->optionValue('id'),
            
        ];
    }

    public function actions(LicenceType $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('licence_types.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-licenceTypeCategory', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn ($row) => auth()->user()->can('update licence_types') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn ($row) => auth()->user()->can('delete licence_types') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        LicenceType::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        LicenceType::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
