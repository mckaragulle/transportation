<?php

namespace App\Livewire\Landlord\BranchType;

use App\Models\Landlord\LandlordBranchType;
use App\Models\Landlord\LandlordBranchTypeCategory;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BranchTypeTable extends PowerGridComponent
{
    use WithExport;
    public ?int $branchTypeCategoryId = null;

    public string $tableName = 'BranchTypeTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            PowerGrid::exportable(fileName: 'sube-seçenekleri')
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
        return LandlordBranchType::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('branch_type_category_id', function ($role) {
                return $role->branch_type_category->name ?? "---";
            })
            ->add('branch_type_id', function ($role) {
                return $role->branch_type->name ?? "---";
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

            Column::make('Şube Kategorisi', 'branch_type_category_id'),
            Column::make('Şube Grubu', 'branch_type_id'),
            Column::make('Şube', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update branch_types'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update branch_types'),
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
        $id = $this->filters['select']['branch_type_category_id'] ?? null;
        $query = LandlordBranchType::query();
        if ($id > 0) {
            $query->where('branch_type_category_id', $id)->whereNull('branch_type_id')->orderBy('branch_type_category_id', 'asc');
        }
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('branch_type_category_id')
                ->dataSource(LandlordBranchTypeCategory::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('branch_type_id')
                ->dataSource($query->get())
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(LandlordBranchType $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('branch_types.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-branchType', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update branch_types') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete branch_types') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        LandlordBranchType::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        LandlordBranchType::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
