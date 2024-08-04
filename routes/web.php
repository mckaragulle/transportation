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

});

//\Illuminate\Support\Facades\Auth::routes();

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
