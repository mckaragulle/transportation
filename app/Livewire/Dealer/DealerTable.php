<?php

namespace App\Livewire\Dealer;

use App\Models\Dealer;
use App\Models\DealerTypeCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

final class DealerTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $dealerCategories;

    public string $tableName = 'DealerTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        $this->dealerCategories = DealerTypeCategory::query()->with(['dealer_types'])->get(['id', 'name']);

        return [
            PowerGrid::exportable(fileName: 'bayiler')
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
        $dealer = Dealer::query()
            ->select(['id', 'number', 'name', 'shortname', 'email', 'phone', 'tax', 'taxoffice', 'status'])
            ->with(['dealer_type_categories:id,name', 'dealer_types:id,dealer_type_category_id,dealer_type_id,name']);
        return $dealer;
    }

    public function relationSearch(): array
    {
        return [
            'dealer_type_categories' => [
                'name',
            ],
            'dealer_types' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');
        foreach ($this->dealerCategories as $c) {
            $fields->add("dealer_type_category_{$c->id}", function ($row) use ($c) {
                $dealer_types = $row->dealer_types->where('dealer_type_category_id', $c->id);
                $name = '';
                foreach ($dealer_types as $dealer_type) {
                    if (isset($dealer_type->dealer_type->name)) {
                        $name = $dealer_type->dealer_type->name . ' -> ';
                    }
                    $name = ($name . $dealer_type->name ?? '') . '<br>';
                }
                return $name;
            });
        }
        $fields
            ->add('number')
            ->add('name')
            ->add('shortname')
            ->add('email')
            ->add('phone')
            ->add('tax')
            ->add('taxoffice')
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
        foreach ($this->dealerCategories as $c) {
            array_push($column, Column::make("{$c->name}", "dealer_type_category_{$c->id}"));
        }


        $column2 = [

            Column::make('Bayi Adı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update dealers'),
                    fallback: '- empty -'
                ),

            Column::make('Telefon', 'phone')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update dealers'),
                    fallback: '- empty -'
                ),

            Column::make('E-posta', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update dealers'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update dealers'),
                    'Aktif',
                    'Pasif',
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

        foreach ($this->dealerCategories as $c) {
            //WORKING
            $filter =  Filter::inputText("dealer_type_category_{$c->id}")
                ->filterRelation('dealer_types', 'name');
            array_push($filters,  $filter);
        }

        return $filters;
    }

    public function actions(Dealer $row): array
    {
        return [
            Button::add('manage')
                ->slot('<i class="fa fa-person"></i>')
                ->route('dealer_managements.edit', ['id' => $row->id])
                ->class('badge badge-primary'),
            Button::add('login')
                ->slot('<i class="fa-solid fa-right-to-bracket"></i>')
                ->class('badge badge-secondary')
                ->dispatch('login-dealer', ['id' => $row->id]),
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('dealers.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-dealer', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update dealers') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete dealers') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Dealer::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Dealer::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
