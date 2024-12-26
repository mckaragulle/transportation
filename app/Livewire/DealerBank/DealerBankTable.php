<?php

namespace App\Livewire\DealerBank;

use App\Models\Dealer;
use App\Models\DealerBank;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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

final class DealerBankTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;

    public string $tableName = 'DealerBankTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            PowerGrid::exportable(fileName: 'Bayi Banka Bilgileri')
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
        $dealer = DealerBank::query();
        if(Auth::getDefaultDriver() == 'dealer'){
            $dealer->where('dealer_id', auth()->user()->id);
        } else if(Auth::getDefaultDriver() == 'users'){
            $dealer->where('dealer_id', auth()->user()->dealer()->id);
        }
        return $dealer;
    }

    public function relationSearch(): array
    {
        return [
            'dealers' => [
                "number",
                "name",
                "shortname",
                "phone",
                "email"
            ],
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
            ->add('dealer_id', function ($role) {
                return $role->dealer->name ?? "---";
            })
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
            Column::make('Bayi Adı', 'dealer_id')
                ->sortable()
                ->searchable(),
            Column::make('Banka Adı', 'bank_id'),
            Column::make('Adres Başlığı', 'iban')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update dealer_banks'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update dealer_banks'),
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
            Filter::select('dealer_id')
                ->dataSource(Dealer::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('bank_id')
                ->dataSource(Bank::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(DealerBank $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('dealer_banks.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-dealer-bank', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update dealer_banks') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete dealer_banks') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        DealerBank::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        DealerBank::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
