<?php

namespace App\Livewire\User;

use App\Models\User;
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
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'UserTable';

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: auth()->user()->id
        );

        return [
            PowerGrid::exportable('personeller')
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
        $user = User::query()->with('roles');
        return $user->where('dealer_id', $this->dealer_id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('dealer_id', function ($role) {
                return $role->dealer->name ?? null;
            })
            ->add('role', function ($role) {
                return $role->roles->first()->name ?? null;
            })
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Bayi', 'dealer_id')
                ->sortable()
                ->searchable(),
            Column::make('Departman', 'role')
                ->searchable(),
            Column::make('Adı Soyadı', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update users'),
                    fallback: '- empty -'
                ),

            Column::make('E-Posta', 'email')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: auth()->user()->can('update users'),
                    fallback: '- empty -'
                ),

            Column::make('Telefon', 'phone')
                ->sortable()
                ->searchable()
                ->editOnClick(
                    hasPermission: 1,
                    fallback: '- empty -'
                ),

            Column::make('DURUM', 'status')
                ->toggleable(
                    auth()->user()->can('update users'),
                    'Aktif',
                    'Pasif',
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
        ];
    }

    public function actions(User $row): array
    {
        return [
            Button::add('view')
                ->slot('<i class="fa fa-pencil"></i>')
                ->route('users.edit', ['id' => $row->id])
                ->class('badge badge-info'),
            Button::add('delete')
                ->slot('<i class="fa fa-trash"></i>')
                ->id()
                ->class('badge badge-danger')
                ->dispatch('delete-user', ['id' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('view')
                ->when(fn($row) => auth()->user()->can('update users') != 1)
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => auth()->user()->can('delete users') != 1)
                ->hide(),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        User::query()->find($id)->update([
            $field => e($value) ? 1 : 0,
        ]);
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        User::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
