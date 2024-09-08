<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\StaffType;
use App\Models\StaffTypeCategory;
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

final class StaffTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $staffCategories;
    public ?int $staffTypeCategoryId = null;

    public bool $multiSort = true;

    public string $tableName = 'StaffTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: auth()->user()->id
        );

        $this->staffCategories = StaffTypeCategory::query()->with(['staff_types'])->get(['id', 'name']);

        return [
            Exportable::make(fileName: 'personeller')
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
        return Staff::query()
            ->with(['staff_type_categories:id,name', 'staff_types:id,staff_type_category_id,staff_type_id,name']);
    }

    public function relationSearch(): array
    {
        return [
            'staff_type_categories' => [
                'name',
            ],
            'staff_types' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');
        foreach ($this->staffCategories as $c) {
            $fields->add("staff_type_category_{$c->id}", function ($row) use ($c) {
                $staff_type = $row->staff_types->where('staff_type_category_id', $c->id)->first();
                $name = '';
                if (isset($staff_type->staff_type->name)) {
                    $name = $staff_type->staff_type->name . ' -> ';
                }
                Log::info($staff_type);
                return ($name . $staff_type->name ?? '') ?? '---';
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
            ->add('id_number')
            ->add('name')
            ->add('surname')
            ->add('phone1')
            ->add('phone2')
            ->add('email')
            ->add('detail')
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
        foreach ($this->staffCategories as $c) {
            array_push($column, Column::make("{$c->name}", "staff_type_category_{$c->id}"));
        }
        $column2 = [
            Column::make('TC KİMLİK NUMARASI', 'id_number')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('ADI', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('SOYADI', 'surname')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('1. TELEFON', 'phone1')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('2. TELEFON', 'phone2')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('E-POSTA', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('AÇIKLAMA', 'detail')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staffs'),
                    fallback: '- empty -'
                ),
            Column::make('DOSYA', 'filename')
                ->sortable()
                ->searchable(),
            Column::make('Durum', 'status')
                ->toggleable(
                    auth()->user()->can('update staffs'),
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
        $filters = [];

        foreach ($this->staffCategories as $c) {
            //WORKING
            $filter =  Filter::inputText("staff_type_category_{$c->id}")
                ->filterRelation('staff_types', 'name');

            array_push($filters,  $filter);
        }

        return $filters;
    }

    public function actions(Staff $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('staffs.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-staff', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update staffs') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete staffs') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Staff::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Staff::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
