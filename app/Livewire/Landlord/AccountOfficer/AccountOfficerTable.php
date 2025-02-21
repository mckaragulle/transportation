<?php

namespace App\Livewire\Landlord\AccountOfficer;

use App\Models\Landlord\LandlordAccount;
use App\Models\Landlord\LandlordAccountOfficer;
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

final class AccountOfficerTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public string $dealer_id;

    public string $tableName = 'AccountOfficerTable';

    public function setUp(): array
    {
        $id = $this->dealer_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "account_officer_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Cari Adresleeri')
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
        $account = LandlordAccountOfficer::query()
            ->whereDealerId($this->dealer_id);
        return $account;
    }

    public function relationSearch(): array
    {
        return [
            'accounts' => [
                "number",
                "name",
                "shortname",
                "phone",
                "email"
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id')
            ->add('account_id', function ($role) {
                return $role->account->name ?? "---";
            })
            ->add('number')
            ->add('name')
            ->add('surname')
            ->add('title')
            ->add('phone1')
            ->add('phone2')
            ->add('email')
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
            Column::make('Cari Adı', 'account_id')
                ->sortable()
                ->searchable(),
            Column::make('Yetkili No', 'number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),
            Column::make('Yetkili Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),
            Column::make('Yetkili Soyadı', 'surname')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),
            Column::make('Yetkili Ünvanı', 'title')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),

            Column::make('1. Telefon', 'phone1')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),
            Column::make('2. Telefon', 'phone2')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),
            Column::make('EPosta Adresi', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_officers'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update account_officers'),
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
            Filter::select('account_id')
                ->dataSource(LandlordAccount::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(LandlordAccountOfficer $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('account_officers.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-account-officer', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update account_officers') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete account_officers') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        LandlordAccountOfficer::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        LandlordAccountOfficer::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
