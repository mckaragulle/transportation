<?php

namespace App\Livewire\Tenant\StaffCompetence;

use App\Models\Tenant\Staff;
use App\Models\Tenant\StaffCompetence;
use App\Models\Tenant\Bank;
use App\Models\Tenant\StaffTypeCategory;
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

final class StaffCompetenceTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public string $staff_id;

    public string $tableName = 'StaffCompetenceTable';

    public function setUp(): array
    {
        $id = $this->staff_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "staff_competence_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Personel Yetkinlik Bilgileri')
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
        $staff = StaffCompetence::query()->whereStaffId($this->staff_id);
        return $staff;
    }

    public function relationSearch(): array
    {
        return [
            
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id')
            ->add('staff_type_category_id', function ($role) {
                return $role->staff_type_category->name ?? "---";
            })
            ->add('expiry_date_at', function ($dish) {
                return Carbon::parse($dish->expiry_date_at)->format('d/m/Y'); //20/01/2024 10:05
            })
            ->add('status')
            ->add('created_at');

        return $fields;
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Yetkinlik Türü', 'staff_type_category_id')
                ->sortable()
                ->searchable(),
            Column::make('Geçerlilik Tarihi', 'expiry_date_at')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staff_competences'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update staff_competences'),
                    trueLabel: 'Aktif',
                    falseLabel: 'Pasif',
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
        return [
            Filter::select('staff_type_category_id')
                ->dataSource(StaffTypeCategory::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::boolean('status')->label('Aktif', 'Pasif'),
        ];
    }

    public function actions(StaffCompetence $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('staff_competences.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-staff_competence', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update staff_competences') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete staff_competences') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        StaffCompetence::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        StaffCompetence::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
