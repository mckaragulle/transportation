<?php

use App\Livewire\Account\AccountEdit;
use App\Livewire\Account\AccountManagement;
use App\Livewire\Account\Accounts;
use App\Livewire\AccountAddress\AccountAddresses;
use App\Livewire\AccountAddress\AccountAddressEdit;
use App\Livewire\AccountBank\AccountBankEdit;
use App\Livewire\AccountBank\AccountBanks;
use App\Livewire\AccountFile\AccountFiles;
use App\Livewire\AccountGroup\AccountGroups;
use App\Livewire\AccountOfficer\AccountOfficerEdit;
use App\Livewire\AccountOfficer\AccountOfficers;
use App\Livewire\City\Cities;
use App\Livewire\City\CityEdit;
use App\Livewire\DealerOfficer\DealerOfficerEdit;
use App\Livewire\Hgs\HgsEdit;
use App\Livewire\Hgs\Hgses;
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

use App\Livewire\AccountTypeCategory\AccountTypeCategories;
use App\Livewire\AccountTypeCategory\AccountTypeCategoryEdit;

use App\Livewire\AccountType\AccountTypes;
use App\Livewire\AccountType\AccountTypeEdit;
use App\Livewire\Bank\BankEdit;
use App\Livewire\Bank\Banks;
use App\Livewire\Dealer\DealerManagement;
use App\Livewire\DealerAddress\DealerAddressEdit;
use App\Livewire\DealerAddress\DealerAddresses;
use App\Livewire\DealerBank\DealerBankEdit;
use App\Livewire\District\DistrictEdit;
use App\Livewire\District\Districts;
use App\Livewire\Fined\FinedEdit;
use App\Livewire\Fined\Fineds;
use App\Livewire\Group\GroupEdit;
use App\Livewire\Group\Groups;
use App\Livewire\HgsTypeCategory\HgsTypeCategories;
use App\Livewire\HgsTypeCategory\HgsTypeCategoryEdit;

use App\Livewire\HgsType\HgsTypes;
use App\Livewire\HgsType\HgsTypeEdit;
use App\Livewire\Licence\LicenceEdit;
use App\Livewire\Licence\Licences;
use App\Livewire\LicenceType\LicenceTypeEdit;
use App\Livewire\LicenceType\LicenceTypes;
use App\Livewire\LicenceTypeCategory\LicenceTypeCategories;
use App\Livewire\LicenceTypeCategory\LicenceTypeCategoryEdit;
use App\Livewire\Locality\Localities;
use App\Livewire\Locality\LocalityEdit;
use App\Livewire\Neighborhood\NeighborhoodEdit;
use App\Livewire\Neighborhood\Neighborhoods;
use App\Livewire\Sector\SectorEdit;
use App\Livewire\Sector\Sectors;
use App\Livewire\Signin;
use App\Livewire\Staff\StaffEdit;
use App\Livewire\Staff\Staffs;
use App\Livewire\StaffType\StaffTypeEdit;
use App\Livewire\StaffType\StaffTypes;
use App\Livewire\StaffTypeCategory\StaffTypeCategories;
use App\Livewire\StaffTypeCategory\StaffTypeCategoryEdit;
use App\Models\AccountAddress;
use App\Models\DealerAddress;

Route::get('/login', Signin::class)->name('login');
Route::get('/', Signin::class);

Route::middleware('auth:admin,dealer,web')->prefix('dashboard')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/elemanlar', Users::class)->name('users.list')->middleware('can:read users');
    Route::get('/eleman/{id}/duzenle', UserEdit::class)->name('users.edit')->middleware('can:update users');

    Route::get('/gruplar', Groups::class)->name('groups.list')->middleware('can:read groups');
    Route::get('/grup/{id}/duzenle', GroupEdit::class)->name('groups.edit')->middleware('can:update groups');

    Route::get('/sektorler', Sectors::class)->name('sectors.list')->middleware('can:read sectors');
    Route::get('/sektor/{id}/duzenle', SectorEdit::class)->name('sectors.edit')->middleware('can:update sectors');

    Route::get('/cariler', Accounts::class)->name('accounts.list')->middleware('can:read accounts');
    Route::get('/cari/{id}/duzenle', AccountEdit::class)->name('accounts.edit')->middleware('can:update accounts');

    Route::get('/hgsler', Hgses::class)->name('hgses.list')->middleware('can:read hgses');
    Route::get('/hgs/{id}/duzenle', HgsEdit::class)->name('hgses.edit')->middleware('can:update hgses');

    Route::get('/bayi-yonetimi/{id}', DealerManagement::class)->name('dealer_managements.edit')->middleware('can:read dealers');
    Route::get('/bayi-adresleri/{id?}/{is_show?}', DealerAddresses::class)->name('dealer_addresses.list')->middleware('can:read dealer_addresses');
    Route::get('/bayi-adresi/{id}/duzenle', DealerAddressEdit::class)->name('dealer_addresses.edit')->middleware('can:update dealer_addresses');
    Route::get('/bayi-banka-bilgisi/{id}/duzenle', DealerBankEdit::class)->name('dealer_banks.edit')->middleware('can:update dealer_banks');
    Route::get('/bayi-yetkili/{id}/duzenle', DealerOfficerEdit::class)->name('dealer_officers.edit')->middleware('can:update dealer_officers');

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

Route::middleware('auth:admin')->prefix('dashboard')->group(function () {

    Route::get('/yoneticiler', Admins::class)->name('admins.list')->middleware('can:read admins');
    Route::get('/yonetici/{id}/duzenle', AdminEdit::class)->name('admins.edit')->middleware('can:update admins');

    Route::get('/roller', Roles::class)->name('roles.list')->middleware('can:read roles');
    Route::get('/rol/{id}/duzenle', RoleEdit::class)->name('roles.edit')->middleware('can:update roles');

    Route::get('/izinler', Permissions::class)->name('permissions.list')->middleware('can:read permissions');
    Route::get('/izin/{id}/duzenle', PermissionEdit::class)->name('permissions.edit')->middleware('can:update permissions');

    Route::get('/bayiler', Dealers::class)->name('dealers.list')->middleware('can:read dealers');
    Route::get('/bayi/{id}/duzenle', DealerEdit::class)->name('dealers.edit')->middleware('can:update dealers');

    Route::get('/markalar', VehicleBrands::class)->name('vehicle_brands.list')->middleware('can:read vehicle_brands');
    Route::get('/marka/{id}/duzenle', VehicleBrandEdit::class)->name('vehicle_brands.edit')->middleware('can:update vehicle_brands');

    Route::get('/iller', Cities::class)->name('cities.list')->middleware('can:read cities');
    Route::get('/il/{id}/duzenle', CityEdit::class)->name('cities.edit')->middleware('can:update cities');
    
    Route::get('/ilceler', Districts::class)->name('districts.list')->middleware('can:read districts');
    Route::get('/ilce/{id}/duzenle', DistrictEdit::class)->name('districts.edit')->middleware('can:update districts');
    
    Route::get('/mahalleler', Neighborhoods::class)->name('neighborhoods.list')->middleware('can:read neighborhoods');
    Route::get('/mahalle/{id}/duzenle', NeighborhoodEdit::class)->name('neighborhoods.edit')->middleware('can:update neighborhoods');
    
    Route::get('/semtler', Localities::class)->name('localities.list')->middleware('can:read localities');
    Route::get('/semt/{id}/duzenle', LocalityEdit::class)->name('localities.edit')->middleware('can:update localities');

    Route::get('/bankalar', Banks::class)->name('banks.list')->middleware('can:read banks');
    Route::get('/banka/{id}/duzenle', BankEdit::class)->name('banks.edit')->middleware('can:update banks');

    Route::get('/tipler', VehicleTickets::class)->name('vehicle_tickets.list')->middleware('can:read vehicle_tickets');
    Route::get('/tip/{id}/duzenle', VehicleTicketEdit::class)->name('vehicle_tickets.edit')->middleware('can:update vehicle_tickets');

    Route::get('/modeller', VehicleModels::class)->name('vehicle_models.list')->middleware('can:read vehicle_models');
    Route::get('/model/{id}/duzenle', VehicleModelEdit::class)->name('vehicle_models.edit')->middleware('can:update vehicle_models');

    Route::get('/ozellik-kategorileri', VehiclePropertyCategories::class)->name('vehicle_property_categories.list')->middleware('can:read vehicle_property_categories');
    Route::get('/ozellik-kategorisi/{id}/duzenle', VehiclePropertyCategoryEdit::class)->name('vehicle_property_categories.edit')->middleware('can:update vehicle_property_categories');

    Route::get('/ozellikler', VehicleProperties::class)->name('vehicle_properties.list')->middleware('can:read vehicle_properties');
    Route::get('/ozellik/{id}/duzenle', VehiclePropertyEdit::class)->name('vehicle_properties.edit')->middleware('can:update vehicle_properties');

    Route::get('/cari-kategorileri', AccountTypeCategories::class)->name('account_type_categories.list')->middleware('can:read account_type_categories');
    Route::get('/cari-kategorisi/{id}/duzenle', AccountTypeCategoryEdit::class)->name('account_type_categories.edit')->middleware('can:update account_type_categories');

    Route::get('/cari-tipleri', AccountTypes::class)->name('account_types.list')->middleware('can:read account_types');
    Route::get('/cari-tipi/{id}/duzenle', AccountTypeEdit::class)->name('account_types.edit')->middleware('can:update account_types');

    Route::get('/hgs-kategorileri', HgsTypeCategories::class)->name('hgs_type_categories.list')->middleware('can:read hgs_type_categories');
    Route::get('/hgs-kategorisi/{id}/duzenle', HgsTypeCategoryEdit::class)->name('hgs_type_categories.edit')->middleware('can:update hgs_type_categories');

    Route::get('/hgs-tipleri', HgsTypes::class)->name('hgs_types.list')->middleware('can:read hgs_types');
    Route::get('/hgs-tipi/{id}/duzenle', HgsTypeEdit::class)->name('hgs_types.edit')->middleware('can:update hgs_types');


    Route::get('/surucu-belgesi-kategorileri', LicenceTypeCategories::class)->name('licence_type_categories.list')->middleware('can:read licence_type_categories');
    Route::get('/surucu-belgesi-kategorisi/{id}/duzenle', LicenceTypeCategoryEdit::class)->name('licence_type_categories.edit')->middleware('can:update licence_type_categories');

    Route::get('/surucu-belgesi-tipleri', LicenceTypes::class)->name('licence_types.list')->middleware('can:read licence_types');
    Route::get('/surucu-belgesi-tipi/{id}/duzenle', LicenceTypeEdit::class)->name('licence_types.edit')->middleware('can:update licence_types');

    Route::get('/personel-kategorileri', StaffTypeCategories::class)->name('staff_type_categories.list')->middleware('can:read staff_type_categories');
    Route::get('/personel-kategorisi/{id}/duzenle', StaffTypeCategoryEdit::class)->name('staff_type_categories.edit')->middleware('can:update staff_type_categories');

    Route::get('/personel-tipleri', StaffTypes::class)->name('staff_types.list')->middleware('can:read staff_types');
    Route::get('/personel-tipi/{id}/duzenle', StaffTypeEdit::class)->name('staff_types.edit')->middleware('can:update staff_types');

});

//\Illuminate\Support\Facades\Auth::routes();

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
