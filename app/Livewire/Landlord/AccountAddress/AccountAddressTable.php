<?php

namespace App\Livewire\Landlord\AccountAddress;

use App\Models\Landlord\LandlordAccountAddress;
use App\Models\Landlord\LandlordCity;
use App\Models\Landlord\LandlordDealer;
use App\Models\Landlord\LandlordDistrict;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AccountAddressTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public string $dealer_id;

    public string $tableName = 'AccountAddressTable';

    public function setUp(): array
    {
        $id = $this->dealer_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "account_address_{$id}"
        );

        return [
            PowerGrid::cache()
                ->ttl(60)
                ->prefix($id . '_'),
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
        $account = LandlordAccountAddress::query()->whereDealerId($this->dealer_id);
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
            ->add('city_id', function ($role) {
                return $role->city->name ?? "---";
            })
            ->add('district_id', function ($role) {
                return $role->district->name ?? "---";
            })
            ->add('neighborhood_id', function ($role) {
                return $role->neighborhood->name ?? "---";
            })
            ->add('locality_id', function ($role) {
                return $role->locality->name ?? "---";
            })
            ->add('name')
            ->add('address1')
            ->add('address2')
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
            Column::make('İl Adı', 'city_id'),
            Column::make('İlçe Adı', 'district_id'),
            Column::make('Mahalle Adı', 'neighborhood_id'),
            Column::make('Semt Adı', 'locality_id'),
            Column::make('Adres Başlığı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),
            Column::make('1. Adres', 'address1')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),
            Column::make('2. Adres', 'address2')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),
            Column::make('1. Telefon', 'phone1')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),
            Column::make('2. Telefon', 'phone2')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),
            Column::make('EPosta Adresi', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update account_addresses'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update account_addresses'),
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
        $city_id = $this->filters['select']['city_id'] ?? null;
        $query = LandlordAccountAddress::query();
        if ($city_id > 0) {
            $query->where('city_id', $city_id)->orderBy('city_id', 'asc');
        }
        $district_id = $this->filters['select']['district_id'] ?? null;
        if ($district_id > 0) {
            $query->where('district_id', $district_id)->orderBy('district_id', 'asc');
        }
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
            Filter::select('city_id')
                ->dataSource(LandlordCity::orderBy('id', 'asc')->get())
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('district_id')
                ->depends(['city_id'])
                ->dataSource(
                    fn($depends) => LandlordDistrict::query()
                        ->when(
                            isset($depends['city_id']),
                            fn(Builder $query) => $query->whereRelation(
                                'city',
                                fn(Builder $builder) => $builder->where('id', $depends['city_id'])
                            )
                        )
                        ->get()
                )
                ->optionLabel('name')
                ->optionValue('id'),

        ];
    }

    public function actions(LandlordAccountAddress $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('account_addresses.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-account_address', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update account_addresses') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete account_addresses') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        LandlordAccountAddress::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        LandlordAccountAddress::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
