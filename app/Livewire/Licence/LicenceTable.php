<?php

namespace App\Livewire\Licence;

use App\Models\Licence;
use App\Models\LicenceType;
use App\Models\LicenceTypeCategory;
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

final class LicenceTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $licenceCategories;
    public ?int $licenceTypeCategoryId = null;

    public bool $multiSort = true;

    public string $tableName = 'LicenceTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: auth()->user()->id
        );

        $this->licenceCategories = LicenceTypeCategory::query()->with(['licence_types'])->get(['id', 'name']);

        return [
            Exportable::make(fileName: 'surucu-belgeleri')
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
        return Licence::query()
            ->select(['id', 'number', 'started_at', 'finished_at', 'detail', 'filename', 'status'])
            ->with(['licence_type_categories:id,name', 'licence_types:id,licence_type_category_id,licence_type_id,name']);
    }

    public function relationSearch(): array
    {
        return [
            'licence_type_categories' => [
                'name',
            ],
            'licence_types' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');
        foreach ($this->licenceCategories as $c) {
            $fields->add("licence_type_category_{$c->id}", function ($row) use ($c) {
                $licence_type = $row->licence_types->where('licence_type_category_id', $c->id)->first();
                $name = '';
                if (isset($licence_type->licence_type->name)) {
                    $name = $licence_type->licence_type->name . ' -> ';
                }
                return ($name . $licence_type->name ?? '') ?? '---';
            });
        }
        $fields->add('number')
            ->add('started_at')
            ->add('finished_at')
            ->add('filename', function ($row) {
                $f = null;
                if (!is_null($row->filename) && Storage::exists($row->filename)) {
                    $f = '<a href="' . Storage::url($row->filename) . '" target="_blank"> <img width="50" src="' . Storage::url($row->filename) . '"></a>';
                }
                return $f;
            })
            ->add('detail')
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
        foreach ($this->licenceCategories as $c) {
            array_push($column, Column::make("{$c->name}", "licence_type_category_{$c->id}"));
        }
        $column2 = [
            Column::make('Sürücü Belgesi Numarası', 'number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update licences'),
                    fallback: '- empty -'
                ),
            Column::make('Başlangıç Tarihi', 'started_at')
                ->sortable()
                ->searchable(),
            Column::make('Bitiş Tarihi', 'finished_at')
                ->sortable()
                ->searchable(),
            Column::make('DOSYA', 'filename')
                ->sortable()
                ->searchable(),
            Column::make('Dosya', 'detail')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update licences'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update licences'),
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
        $filters = [];

        foreach ($this->licenceCategories as $c) {
            //WORKING
            $filter =  Filter::inputText("licence_type_category_{$c->id}")
                ->filterRelation('licence_types', 'name');

            array_push($filters,  $filter);
        }

        return $filters;
    }

    public function actions(Licence $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('licences.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-licence', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update licences') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete licences') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Licence::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Licence::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
