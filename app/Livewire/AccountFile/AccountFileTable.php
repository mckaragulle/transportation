<?php

namespace App\Livewire\AccountFile;

use App\Models\Account;
use App\Models\AccountFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

final class AccountFileTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public string $dealer_id;

    public string $tableName = 'AccountFileTable';

    public function setUp(): array
    {
        $id = $this->dealer_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "account_file_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Cari Dosyaları')
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
        $account = AccountFile::query()
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
            ->add('title')
            ->add('filename', function ($dish) {
                return '<a href="' . Storage::url($dish->filename) . '" target="_blank">' . $dish->title . '</a>';
            });;

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
            Column::make('Dosya Adı', 'title')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_files'),
                    fallback: '- empty -'
                ),
            Column::make('Dosya', 'filename'),
            Column::action('EYLEMLER')
                ->visibleInExport(visible: false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('account_id')
                ->dataSource(Account::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(AccountFile $row): array
    {
        return [
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-account-file', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete account_files') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        AccountFile::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        AccountFile::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
