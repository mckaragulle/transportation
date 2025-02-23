<?php

namespace App\Livewire\Tenant\StaffBank;

use App\Models\Tenant\Staff;
use App\Models\Tenant\StaffBank;
use App\Models\Tenant\Bank;
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

final class StaffBankTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public null|string $staff_id = null;

    public string $tableName = 'StaffBankTable';

    public function setUp(): array
    {
        $id = $this->staff_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "staff_bank_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Personel Banka Bilgileri')
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
        $staff = StaffBank::query()->whereStaffId($this->staff_id);
        return $staff;
    }

    public function relationSearch(): array
    {
        return [
            'banks' => [
                "name",
                "eft",
                "swift",
                "email",
                "phone"
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id')
            ->add('bank_id', function ($role) {
                return $role->bank->name ?? "---";
            })
            ->add('iban')
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
            Column::make('Banka Adı', 'bank_id'),
            Column::make('Adres Başlığı', 'iban')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staff_banks'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update staff_banks'),
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
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('staff_id')
                ->dataSource(Staff::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('bank_id')
                ->dataSource(Bank::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(StaffBank $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('tenant.staff_banks.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-staff-bank', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update staff_banks') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete staff_banks') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        StaffBank::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        StaffBank::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
