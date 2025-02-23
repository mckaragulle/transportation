<?php

namespace App\Livewire\Tenant\StaffFile;

use App\Models\Tenant\StaffFile;
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

final class StaffFileTable extends PowerGridComponent
{
    use WithExport;

    public bool $multiSort = true;
    public null|string $staff_id;

    public string $tableName = 'StaffFileTable';

    public function setUp(): array
    {

        $id = $this->staff_id;
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filter', 'sort'],
            prefix: "staff_file_{$id}"
        );

        return [
            PowerGrid::exportable(fileName: 'Personel Dosyaları')
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
        $staff = StaffFile::query()
            ->whereStaffId($this->staff_id);
        return $staff;
    }

    public function relationSearch(): array
    {
        return [
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
            Column::make('Dosya Adı', 'title')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update staff_files'),
                    fallback: '- empty -'
                ),
            Column::make('Dosya', 'filename'),
            Column::make('DURUM', 'status')
                ->toggleable(
                    hasPermission: auth()->user()->can('update staff_files'),
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

    public function actions(StaffFile $row): array
    {
        return [
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-staff-file', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete staff_files') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        StaffFile::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        StaffFile::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
