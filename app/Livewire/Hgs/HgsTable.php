<?php

namespace App\Livewire\Hgs;

use App\Models\Hgs;
use App\Models\HgsType;
use App\Models\HgsTypeCategory;
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

final class HgsTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $hgsCategories;
    public ?int $hgsTypeCategoryId = null;

    public string $tableName = 'HgsTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        $this->hgsCategories = HgsTypeCategory::query()->get(['id', 'name']);

        return [
            Exportable::make(fileName: 'hgsler')
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
        return Hgs::query()->select(['id', 'number', 'filename', 'buyed_at', 'canceled_at'])->with(['hgs_type_categories:id,name', 'hgs_types']);
    }

    public function relationSearch(): array
    {
        return [
            // 'hgs_type_category' => [ // relationship on dishes model
            //     'name',
            //     'slug',
            // ],
            // 'hgs_type' => [ // relationship on dishes model
            //     'name',
            //     'slug',
            // ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');
        foreach ($this->hgsCategories as $c) {
            $fields->add("hgs_category_{$c->id}", function($row){
                Log::info($row);
                return $row->number;
                // return $c->hgs_type_categories->first()->name;
            });
        }
        $fields->add('number')
            ->add('filename', function ($row) {
                $f = null;
                if (!is_null($row->filename) && Storage::exists($row->filename)) {
                    $f = '<a href="' . Storage::url($row->filename) . '" target="_blank"> <img width="50" src="' . Storage::url($row->filename) . '"></a>';
                }
                return $f;
            })
            ->add('buyed_at')
            ->add('canceled_at')
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
        foreach ($this->hgsCategories as $c) {
            array_push($column, Column::make("{$c->name}", "hgs_category_{$c->id}"));
        }
        
        $column2 = [
            Column::make('Hgs Numarası', 'number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update hgses'),
                    fallback: '- empty -'
                ),
            Column::make('Dosya', 'filename')
                ->sortable()
                ->searchable(),
            Column::make('Alınma Tarihi', 'buyed_at')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update hgses'),
                    fallback: '- empty -'
                ),
            Column::make('İptal Edilme Tarihi', 'canceled_at')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update hgses'),
                    fallback: '- empty -'
                ),

            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update hgses'),
                    'Aktif',
                    'Pasif',
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
        // $id = $this->filters['select']['hgs_type_category_id'] ?? null;
        // $query = HgsType::query();
        // if ($id > 0) {
        //     $query->where('hgs_type_category_id', $id)->whereNull('hgs_type_id')->orderBy('hgs_type_category_id', 'asc');
        // }
        // return [
        //     Filter::select('hgs_category')
        //         ->dataSource(HgsTypeCategory::orderBy('id', 'asc')->get())
        //         ->optionLabel('name')
        //         ->optionValue('id'),
        //     Filter::select('hgs_type')
        //         ->dataSource($query->get())
        //         ->optionLabel('name')
        //         ->optionValue('id'),

        // ];
        return [];
    }

    public function actions(Hgs $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('hgses.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-hgs', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update hgses') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete hgses') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        HgsType::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        HgsType::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}