<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Admin\Admins;
use App\Livewire\Admin\AdminEdit;
use App\Livewire\Role\Roles;
use App\Livewire\Role\RoleEdit;
use App\Livewire\Permission\Permissions;
use App\Livewire\Permission\PermissionEdit;

use App\Livewire\Dealer\Dealers;
use App\Livewire\Dealer\DealerEdit;

use App\Livewire\User\Users;
use App\Livewire\User\UserEdit;

use App\Livewire\VehicleBrand\VehicleBrands;
use App\Livewire\VehicleBrand\VehicleBrandEdit;

use App\Livewire\VehicleTicket\VehicleTickets;
use App\Livewire\VehicleTicket\VehicleTicketEdit;

use App\Livewire\VehicleModel\VehicleModels;
use App\Livewire\VehicleModel\VehicleModelEdit;

use App\Livewire\VehiclePropertyCategory\VehiclePropertyCategories;
use App\Livewire\VehiclePropertyCategory\VehiclePropertyCategoryEdit;

use App\Livewire\VehicleProperty\VehicleProperties;
use App\Livewire\VehicleProperty\VehiclePropertyEdit;

use App\Livewire\Signin;


Route::get('/login', Signin::class)->name('login');
Route::get('/', Signin::class);

Route::middleware('auth:admin,dealer,web')->prefix('dashboard')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/personeller', Users::class)->name('users.list')->middleware('can:read users');
    Route::get('/personel/{id}/duzenle', UserEdit::class)->name('users.edit')->middleware('can:update users');
});

Route::middleware('auth:admin')->prefix('dashboard')->group(function () {

    Route::get('/yoneticiler', Admins::class)->name('admins.list')->middleware('can:read admins');
    Route::get('/yonetici/{id}/duzenle', AdminEdit::class)->name('admins.edit')->middleware('can:update admins');

    Route::get('/roller', Roles::class)->name('roles.list')->middleware('can:read roles');
    Route::get('/rol/{id}/duzenle', RoleEdit::class)->name('roles.edit')->middleware('can:update roles');

    Route::get('/izinler', Permissions::class)->name('permissions.list')->middleware('can:read permissions');
    Route::get('/izin/{id}/duzenle', PermissionEdit::class)->name('permissions.edit')->middleware('can:update permissions');

    Route::get('/bayiler', Dealers::class)->name('dealers.list')->middleware('can:read dealers');
    Route::get('/bayi/{id}/duzenle', DealerEdit::class)->name('dealers.edit')->middleware('can:update dealers');

    Route::get('/markalar', VehicleBrands::class)->name('vehicleBrands.list')->middleware('can:read vehicleBrands');
    Route::get('/marka/{id}/duzenle', VehicleBrandEdit::class)->name('vehicleBrands.edit')->middleware('can:update vehicleBrands');

    Route::get('/tipler', VehicleTickets::class)->name('vehicleTickets.list')->middleware('can:read vehicleTickets');
    Route::get('/motipdel/{id}/duzenle', VehicleTicketEdit::class)->name('vehicleTickets.edit')->middleware('can:update vehicleTickets');

    Route::get('/modeller', VehicleModels::class)->name('vehicleModels.list')->middleware('can:read vehicleModels');
    Route::get('/model/{id}/duzenle', VehicleModelEdit::class)->name('vehicleModels.edit')->middleware('can:update vehicleModels');

    Route::get('/ozellik-kategorileri', VehiclePropertyCategories::class)->name('vehiclePropertyCategories.list')->middleware('can:read vehiclePropertyCategories');
    Route::get('/ozellik-kategorisi/{id}/duzenle', VehiclePropertyCategoryEdit::class)->name('vehiclePropertyCategories.edit')->middleware('can:update vehiclePropertyCategories');

    Route::get('/ozellikler', VehicleProperties::class)->name('vehicleProperties.list')->middleware('can:read vehicleProperties');
    Route::get('/ozellik/{id}/duzenle', VehiclePropertyEdit::class)->name('vehicleProperties.edit')->middleware('can:update vehicleProperties');
});

//\Illuminate\Support\Facades\Auth::routes();

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
