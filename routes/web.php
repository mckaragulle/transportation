<?php

use App\Livewire\Account\AccountEdit;
use App\Livewire\Account\AccountManagement;
use App\Livewire\Account\Accounts;
use App\Livewire\AccountAddress\AccountAddresses;
use App\Livewire\AccountAddress\AccountAddressEdit;
use App\Livewire\AccountBank\AccountBankEdit;
use App\Livewire\AccountOfficer\AccountOfficerEdit;
use App\Livewire\Hgs\HgsEdit;
use App\Livewire\Hgs\Hgses;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\User\Users;
use App\Livewire\User\UserEdit;
use App\Livewire\Fined\FinedEdit;
use App\Livewire\Fined\Fineds;
use App\Livewire\Licence\LicenceEdit;
use App\Livewire\Licence\Licences;
use App\Livewire\Signin;
use App\Livewire\Staff\StaffEdit;
use App\Livewire\Staff\Staffs;

Route::get('/login', Signin::class)->name('login');
Route::get('/', Signin::class);

Route::middleware('auth:dealer,web')->prefix('dashboard')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/elemanlar', Users::class)->name('users.list')->middleware('can:read users');
    Route::get('/eleman/{id}/duzenle', UserEdit::class)->name('users.edit')->middleware('can:update users');

    Route::get('/cariler', Accounts::class)->name('accounts.list')->middleware('can:read accounts');
    Route::get('/cari/{id}/duzenle', AccountEdit::class)->name('accounts.edit')->middleware('can:update accounts');

    Route::get('/hgsler', Hgses::class)->name('hgses.list')->middleware('can:read hgses');
    Route::get('/hgs/{id}/duzenle', HgsEdit::class)->name('hgses.edit')->middleware('can:update hgses');

    Route::get('/cari-adresleri/{id?}/{is_show?}', AccountAddresses::class)->name('account_addresses.list')->middleware('can:read account_addresses');
    Route::get('/cari-adresi/{id}/duzenle', AccountAddressEdit::class)->name('account_addresses.edit')->middleware('can:update account_addresses');
    Route::get('/cari-banka-bilgisi/{id}/duzenle', AccountBankEdit::class)->name('account_banks.edit')->middleware('can:update account_banks');
    Route::get('/cari-yetkili/{id}/duzenle', AccountOfficerEdit::class)->name('account_officers.edit')->middleware('can:update account_officers');

    Route::get('/cari-yonetimi/{id}', AccountManagement::class)->name('account_managements.edit')->middleware('can:read accounts');

    Route::get('/surucu-belgeleri', Licences::class)->name('licences.list')->middleware('can:read licences');
    Route::get('/surucu-belgesi/{id}/duzenle', LicenceEdit::class)->name('licences.edit')->middleware('can:update licences');

    Route::get('/personeller', Staffs::class)->name('staffs.list')->middleware('can:read staffs');
    Route::get('/personel/{id}/duzenle', StaffEdit::class)->name('staffs.edit')->middleware('can:update staffs');

    Route::get('/arac-cezalari', Fineds::class)->name('fineds.list')->middleware('can:read fineds');
    Route::get('/arac-cezasi/{id}/duzenle', FinedEdit::class)->name('fineds.edit')->middleware('can:update fineds');
});