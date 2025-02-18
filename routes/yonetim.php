<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandLord\AdminSignin;
use App\Livewire\LandLord\City\{Cities, CityEdit};
use App\Livewire\Dashboard;

use App\Livewire\Landlord\StaffType\{StaffTypeEdit, StaffTypes};
use App\Livewire\Landlord\StaffTypeCategory\{StaffTypeCategories, StaffTypeCategoryEdit};
use App\Livewire\Landlord\AccountType\{AccountTypeEdit, AccountTypes};
use App\Livewire\Landlord\AccountTypeCategory\{AccountTypeCategories, AccountTypeCategoryEdit};
use App\Livewire\Landlord\Group\{Groups, GroupEdit};
use App\Livewire\Landlord\Sector\{Sectors, SectorEdit};

use App\Livewire\Admin\{Admins, AdminEdit};
use App\Livewire\Role\{Roles, RoleEdit};
use App\Livewire\Permission\{Permissions, PermissionEdit};

use App\Livewire\Dealer\{Dealers, DealerEdit};
use App\Livewire\Landlord\VehicleBrand\{VehicleBrands, VehicleBrandEdit};
use App\Livewire\Landlord\VehicleTicket\{VehicleTickets, VehicleTicketEdit};
use App\Livewire\Landlord\VehicleModel\{VehicleModels, VehicleModelEdit};
use App\Livewire\Landlord\VehiclePropertyCategory\{VehiclePropertyCategories, VehiclePropertyCategoryEdit};

use App\Livewire\Landlord\VehicleProperty\{VehicleProperties, VehiclePropertyEdit};
use App\Livewire\Landlord\Bank\{BankEdit, Banks};
use App\Livewire\Dealer\DealerManagement;
use App\Livewire\Landlord\DealerType\{DealerTypeEdit, DealerTypes};
use App\Livewire\Landlord\DealerTypeCategory\{DealerTypeCategories, DealerTypeCategoryEdit};
use App\Livewire\Landlord\District\{DistrictEdit, Districts};
use App\Livewire\Landlord\HgsTypeCategory\{HgsTypeCategories, HgsTypeCategoryEdit};

use App\Livewire\Landlord\HgsType\{HgsTypes, HgsTypeEdit};
use App\Livewire\Landlord\LicenceType\{LicenceTypeEdit, LicenceTypes};
use App\Livewire\Landlord\LicenceTypeCategory\{LicenceTypeCategories, LicenceTypeCategoryEdit};
use App\Livewire\Landlord\Locality\{Localities, LocalityEdit};
use App\Livewire\Landlord\Neighborhood\{NeighborhoodEdit, Neighborhoods};

Route::get('/', AdminSignin::class)->name('admin.login');

Route::middleware('auth:admin')->group(function () {
    Route::get('/panel', Dashboard::class)->name('yonetim');
    Route::get('/yoneticiler', Admins::class)->name('admins.list')->middleware('can:read admins');
    Route::get('/yonetici/{id}/duzenle', AdminEdit::class)->name('admins.edit')->middleware('can:update admins');

    Route::get('/roller', Roles::class)->name('roles.list')->middleware('can:read roles');
    Route::get('/rol/{id}/duzenle', RoleEdit::class)->name('roles.edit')->middleware('can:update roles');

    Route::get('/izinler', Permissions::class)->name('permissions.list')->middleware('can:read permissions');
    Route::get('/izin/{id}/duzenle', PermissionEdit::class)->name('permissions.edit')->middleware('can:update permissions');

    Route::get('/gruplar', Groups::class)->name('groups.list')->middleware('can:read groups');
    Route::get('/grup/{id}/duzenle', GroupEdit::class)->name('groups.edit')->middleware('can:update groups');

    Route::get('/sektorler', Sectors::class)->name('sectors.list')->middleware('can:read sectors');
    Route::get('/sektor/{id}/duzenle', SectorEdit::class)->name('sectors.edit')->middleware('can:update sectors');

    Route::get('/bayi-kategorileri', DealerTypeCategories::class)->name('dealer_type_categories.list')->middleware('can:read dealer_type_categories');
    Route::get('/bayi-kategorisi/{id}/duzenle', DealerTypeCategoryEdit::class)->name('dealer_type_categories.edit')->middleware('can:update dealer_type_categories');

    Route::get('/bayi-tipleri', DealerTypes::class)->name('dealer_types.list')->middleware('can:read dealer_types');
    Route::get('/bayi-tipi/{id}/duzenle', DealerTypeEdit::class)->name('dealer_types.edit')->middleware('can:update dealer_types');

    Route::get('/bayiler', Dealers::class)->name('dealers.list')->middleware('can:read dealers');
    Route::get('/bayi/{id}/duzenle', DealerEdit::class)->name('dealers.edit')->middleware('can:update dealers');
    Route::get('/bayi-yonetimi/{id}', DealerManagement::class)->name('dealer_managements.edit')->middleware('can:read dealers');

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

    Route::get('/cariler', \App\Livewire\Landlord\Account\Accounts::class)->name('accounts.list')->middleware('can:read accounts');
    Route::get('/cari/{id}/duzenle', \App\Livewire\Landlord\Account\AccountEdit::class)->name('accounts.edit')->middleware('can:update accounts');

    Route::get('/surucu-belgesi-kategorileri', LicenceTypeCategories::class)->name('licence_type_categories.list')->middleware('can:read licence_type_categories');
    Route::get('/surucu-belgesi-kategorisi/{id}/duzenle', LicenceTypeCategoryEdit::class)->name('licence_type_categories.edit')->middleware('can:update licence_type_categories');

    Route::get('/surucu-belgesi-tipleri', LicenceTypes::class)->name('licence_types.list')->middleware('can:read licence_types');
    Route::get('/surucu-belgesi-tipi/{id}/duzenle', LicenceTypeEdit::class)->name('licence_types.edit')->middleware('can:update licence_types');

    Route::get('/hgs-kategorileri', HgsTypeCategories::class)->name('hgs_type_categories.list')->middleware('can:read hgs_type_categories');
    Route::get('/hgs-kategorisi/{id}/duzenle', HgsTypeCategoryEdit::class)->name('hgs_type_categories.edit')->middleware('can:update hgs_type_categories');

    Route::get('/hgs-tipleri', HgsTypes::class)->name('hgs_types.list')->middleware('can:read hgs_types');
    Route::get('/hgs-tipi/{id}/duzenle', HgsTypeEdit::class)->name('hgs_types.edit')->middleware('can:update hgs_types');

    Route::get('/personel-kategorileri', StaffTypeCategories::class)->name('staff_type_categories.list')->middleware('can:read staff_type_categories');
    Route::get('/personel-kategorisi/{id}/duzenle', StaffTypeCategoryEdit::class)->name('staff_type_categories.edit')->middleware('can:update staff_type_categories');

    Route::get('/personel-tipleri', StaffTypes::class)->name('staff_types.list')->middleware('can:read staff_types');
    Route::get('/personel-tipi/{id}/duzenle', StaffTypeEdit::class)->name('staff_types.edit')->middleware('can:update staff_types');
});
