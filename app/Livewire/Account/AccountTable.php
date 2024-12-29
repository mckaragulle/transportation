<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\AccountTypeCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AccountTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $accountCategories;
    public ?int $accountTypeCategoryId = null;

    public bool $multiSort = true;
    public string $dealer_id;

    public string $tableName = 'AccountTable';

    public function setUp(): array
    {
        $id = $this->dealer_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "account_{$id}"
        );

        $this->accountCategories = AccountTypeCategory::query()->with(['account_types'])->get(['id', 'name']);

        return [
            PowerGrid::exportable(fileName: 'Cariler')
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
        $account = Account::query()
            ->select(['id', 'dealer_id', 'number', 'name', 'shortname', 'email', 'phone', 'tax', 'taxoffice', 'status'])
            ->with(['account_type_categories:id,name', 'account_types:id,account_type_category_id,account_type_id,name', 'dealer:id,name'])->whereDealerId($this->dealer_id);;
        return $account;
        // return Account::query()
        //     ->select(['id', 'number', 'name', 'shortname', 'email', 'phone', 'detail', 'status'])
        //     ->with(['account_type_categories:id,name', 'account_types:id,account_type_category_id,account_type_id,name']);
    }

    public function relationSearch(): array
    {
        return [
            'account_type_categories' => [
                'name',
            ],
            'account_types' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');
        foreach ($this->accountCategories as $c) {
            $fields->add("account_type_category_{$c->id}", function ($row) use ($c) {
                $account_types = $row->account_types->where('account_type_category_id', $c->id);
                $name = '';
                foreach ($account_types as $account_type) {
                    if (isset($account_type->account_type->name)) {
                        $name = $account_type->account_type->name . ' -> ';
                    }
                    $name = ($name . $account_type->name ?? '') . '<br>';
                }
                return $name;
            });
        }
        $fields
            ->add('dealer_id', function ($role) {
                return $role->dealer->name ?? "---";
            })
            ->add('number')
            ->add('name')
            ->add('shortname')
            ->add('email')
            ->add('phone')
            ->add('tax')
            ->add('taxoffice')
            // ->add('filename', function ($row) {
            //     $f = null;
            //     if (!is_null($row->filename) && Storage::exists($row->filename)) {
            //         $f = '<a href="' . Storage::url($row->filename) . '" target="_blank"> <img width="50" src="' . Storage::url($row->filename) . '"></a>';
            //     }
            //     return $f;
            // })
            ->add('status')
            ->add('created_at');

        return $fields;
    }

    public function columns(): array
    {
        $column = [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Bayi Adı', 'dealer_id')
                ->sortable()
                ->searchable(),
        ];
        foreach ($this->accountCategories as $c) {
            array_push($column, Column::make("{$c->name}", "account_type_category_{$c->id}"));
        }
        $column2 = [
            Column::make('Müşteri Cari Numarası', 'number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('Müşteri Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('Müşteri Kısa Adı', 'shortname')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('Müşteri Telefonu', 'phone')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('Müşteri Eposta Adresi', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('TC Kimlik / Vergi Numarası', 'tax')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            Column::make('Vergi Dairesi', 'taxoffice')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update accounts'),
                    fallback: '- empty -'
                ),
            // Column::make('DOSYA', 'filename')
            //     ->sortable()
            //     ->searchable(),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update accounts'),
                    trueLabel: 'Aktif',
                    falseLabel: 'Pasif',
                ),

            Column::make('OLUŞTURULMA TARİHİ', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('EYLEMLER')
                ->visibleInExport(visible: false),
        ];

        return array_merge($column, $column2);
    }

    public function filters(): array
    {
        $filters = [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
        ];

        foreach ($this->accountCategories as $c) {
            //WORKING
            $filter =  Filter::inputText("account_type_category_{$c->id}")
                ->filterRelation('account_types', 'name');

            array_push($filters,  $filter);
        }

        return $filters;
    }

    public function actions(Account $row): array
    {
        return [
            Button::add('manage')
                ->slot('<i class="fa fa-person"></i>')
                ->route('account_managements.edit', ['id' => $row->id])
                ->class('badge badge-primary'),
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('accounts.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-account', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('manage')
                ->when(fn($row) => auth()->user()->can('update accounts') != 1)
                ->hide(),
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update accounts') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete accounts') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Account::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Account::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
