<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\AccountTypeCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

final class AccountTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $accountCategories;
    public ?int $accountTypeCategoryId = null;

    public bool $multiSort = true;

    public string $tableName = 'AccountTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: auth()->user()->id
        );

        $this->accountCategories = AccountTypeCategory::query()->with(['account_types'])->get(['id', 'name']);

        return [
            Exportable::make(fileName: 'Cariler')
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
        return Account::query()
            ->select(['id', 'name', 'email', 'phone', 'address', 'detail', 'filename', 'status'])
            ->with(['account_type_categories:id,name', 'account_types:id,account_type_category_id,account_type_id,name']);
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
                $account_type = $row->account_types->where('account_type_category_id', $c->id)->first();
                $name = '';
                if (isset($account_type->account_type->name)) {
                    $name = $account_type->account_type->name . ' -> ';
                }
                return ($name . $account_type->name ?? '') ?? '---';
            });
        }
        $fields->add('name')
        ->add('email')
        ->add('phone')
            ->add('filename', function ($row) {
                $f = null;
                if (!is_null($row->filename) && Storage::exists($row->filename)) {
                    $f = '<a href="' . Storage::url($row->filename) . '" target="_blank"> <img width="50" src="' . Storage::url($row->filename) . '"></a>';
                }
                return $f;
            })
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
        ];
        foreach ($this->accountCategories as $c) {
            array_push($column, Column::make("{$c->name}", "account_type_category_{$c->id}"));
        }
        $column2 = [
            Column::make('Müşteri Adı', 'name')
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
            Column::make('Dosya', 'filename')
                ->sortable()
                ->searchable(),
        
            Column::make('Durum', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update accounts'),
                    trueLabel: 'Aktif',
                    falseLabel: 'Pasif',
                ),

            Column::make('Oluşturulma Tarihi', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Eylemler')
                ->visibleInExport(visible: false),
        ];

        return array_merge($column, $column2);
    }

    public function filters(): array
    {
        $filters = [];

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