<?php

namespace App\Livewire\Tenant\StaffCompetence;

use App\Models\Tenant\StaffCompetence;
use App\Models\Tenant\StaffType;
use App\Models\Tenant\StaffTypeCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

final class StaffCompetenceTable extends PowerGridComponent
{
    use WithExport;

    public ?Collection $staffTypeCategories;

    public bool $multiSort = true;
    public null|string $staff_id = null;

    public string $tableName = 'StaffCompetenceTable';

    public function setUp(): array
    {

        $this->staffTypeCategories = StaffTypeCategory::query()
        ->with(['staff_types:id,staff_type_category_id,staff_type_id,name'])
        ->whereIn('target', ['all', 'competence'])
        ->get(['id', 'name']);
        $id = $this->staff_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "staff_competence_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Personel Yetkinlik Bilgileri')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSoftDeletes()
                // ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage(perPage: 50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $staff = StaffCompetence::query()->whereStaffId($this->staff_id)
        ->with(['staff_type_category:id,name', 'staff_type:id,staff_type_category_id,staff_type_id,name']);
        return $staff;
    }

    public function relationSearch(): array
    {
        return [
            'staff_type_category' => [
                'name',
            ],
            'staff_type' => [
                'name',
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id');

            foreach ($this->staffTypeCategories as $c) {

                $fields->add($c->id, function ($row) use ($c) {
                    $staff_type = $row->staff_types->where('staff_type_category_id', $c->id)->first();

                    $name = '';

                    if (isset($staff_type->staff_type) && $staff_type->staff_type !== null) {
                        $name = $staff_type?->staff_type?->name . ' -> ';
                    }

                    return ($name . $staff_type?->name ?? '') ?? '---';
                });
            }


            $fields->add('expiry_date_at', function ($dish) {
                return $dish->expiry_date_at === null ? 'SÜRESİZ':Carbon::parse($dish->expiry_date_at)->format('d/m/Y');
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
        foreach ($this->staffTypeCategories as $c) {
            array_push($column, Column::make($c->name, $c->id, "staff_type.name")
            ->sortable()
            ->searchable()
        );
        }
        $column2 = [

            Column::make('Geçerlilik Tarihi', 'expiry_date_at')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staff_competences'),
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update staff_competences'),
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
        // foreach ($this->staffTypeCategories as $c) {
        //     //TODO: BURASI HEM İNPUT HEMDE SEÇENEK OLARAK ÇALIŞIYOR.
        //     // $filter =  Filter::inputText($c->id, $c->id)->filterRelation('staff_type', 'name');
        //     $filter = Filter::select($c->id, 'staff_type_id')
        //     ->dataSource($c->staff_types->sortBy('name', SORT_NATURAL))
        //     ->optionLabel('name')
        //     ->optionValue('id');
        //     $filters[] = $filter;
        // }

        return $filters;

    }

    public function actions(StaffCompetence $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('tenant.staff_competences.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-staff_competence', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update staff_competences') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete staff_competences') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        StaffCompetence::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        StaffCompetence::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
