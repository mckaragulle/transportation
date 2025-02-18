<?php

use App\Livewire\Tenant\Account\{AccountEdit, AccountManagement, Accounts};
use App\Livewire\Tenant\AccountAddress\{AccountAddresses, AccountAddressEdit};
use App\Livewire\Tenant\AccountOfficer\AccountOfficerEdit;
use App\Livewire\Tenant\AccountBank\AccountBankEdit;

use App\Livewire\Tenant\Hgs\{HgsEdit, Hgses};
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Tenant\User\{Users, UserEdit};
use App\Livewire\Tenant\Fined\{FinedEdit, Fineds};
use App\Livewire\Tenant\Licence\{LicenceEdit, Licences};
use App\Livewire\Signin;
use App\Livewire\Tenant\Staff\{StaffEdit, Staffs};

use App\Livewire\Dealer\{Dealers, DealerEdit, DealerManagement};

Route::get('/', Signin::class)->name('login');
Route::middleware(['tenant', 'auth:dealer,web'])
    ->prefix('panel')
    ->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');


        Route::get('/bayiler', Dealers::class)->name('dealers.list')->middleware('can:read dealers');
        Route::get('/bayi/{id}/duzenle', DealerEdit::class)->name('dealers.edit')->middleware('can:update dealers');

        Route::get('/elemanlar', Users::class)->name('users.list')->middleware('can:read users');
        Route::get('/eleman/{id}/duzenle', UserEdit::class)->name('users.edit')->middleware('can:update users');

        // Route::get('/gruplar', Groups::class)->name('groups.list')->middleware('can:read groups');
        // Route::get('/grup/{id}/duzenle', GroupEdit::class)->name('groups.edit')->middleware('can:update groups');

        // Route::get('/sektorler', Sectors::class)->name('sectors.list')->middleware('can:read sectors');
        // Route::get('/sektor/{id}/duzenle', SectorEdit::class)->name('sectors.edit')->middleware('can:update sectors');

        Route::get('/cariler', Accounts::class)->name('accounts.list')->middleware('can:read accounts');
        Route::get('/cari/{id}/duzenle', AccountEdit::class)->name('accounts.edit')->middleware('can:update accounts');

        Route::get('/hgsler', Hgses::class)->name('hgses.list')->middleware('can:read hgses');
        Route::get('/hgs/{id}/duzenle', HgsEdit::class)->name('hgses.edit')->middleware('can:update hgses');

        Route::get('/bayi-yonetimi/{id}', DealerManagement::class)->name('dealer_managements.edit')->middleware('can:read dealers');
        // Route::get('/bayi-adresleri/{id?}/{is_show?}', DealerAddresses::class)->name('dealer_addresses.list')->middleware('can:read dealer_addresses');
        // Route::get('/bayi-adresi/{id}/duzenle', DealerAddressEdit::class)->name('dealer_addresses.edit')->middleware('can:update dealer_addresses');
        // Route::get('/bayi-banka-bilgisi/{id}/duzenle', DealerBankEdit::class)->name('dealer_banks.edit')->middleware('can:update dealer_banks');
        // Route::get('/bayi-yetkili/{id}/duzenle', DealerOfficerEdit::class)->name('dealer_officers.edit')->middleware('can:update dealer_officers');

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
