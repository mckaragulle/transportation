<?php

namespace App\Livewire\Tenant\DealerLogo;

use App\Models\Tenant\DealerLogo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
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

final class DealerLogoTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public null|string $dealer_id = null;

    public string $tableName = 'DealerLogoTable';

    public function setUp(): array
    {
        $id = $this->dealer_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "dealer_logo_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Bayi Logoları')
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
        $dealer = DealerLogo::query()
            ->whereDealerId($this->dealer_id)
            ->orderBy('created_at', 'desc');
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
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('title')
            ->add('filename', function ($dish) {
                return '<a href="' . Storage::url($dish->filename) . '" target="_blank">' . $dish->title . '</a>';
            })
            ->add('status');

        return $fields;
    }

    public function columns(): array
    {
        return [
            Column::make('Logo Adı', 'title')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update dealer_logos'),
                    fallback: '- empty -'
                ),
            Column::make('Logo', 'filename'),
            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update dealer_logos'),
                    trueLabel: 'Aktif',
                    falseLabel: 'Pasif',
                ),
            Column::action('EYLEMLER')
                ->visibleInExport(visible: false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('status')->label('Aktif', 'Pasif'),
        ];
    }

    public function actions(DealerLogo $row): array
    {
        return [
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-dealer-logo', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete dealer_logos') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        DealerLogo::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        DealerLogo::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
