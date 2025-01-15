<div class="dropdown-menu dropdown-menu-end">
    @can('read admins')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('admins.list') }}"><i class="fas fa-user-tie text-danger"></i>
            <span class="ms-2">Yöneticiler</span></a>
    @endcan
    @can('read roles')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('roles.list') }}"><i class="fas fa-user-secret text-danger"></i>
            <span class="ms-2">Roller</span></a>
    @endcan
    @can('read permissions')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('permissions.list') }}"><i
                class="fas fa-fingerprint text-danger"></i> <span
                class="ms-2">İzinler</span></a>
    @endcan
    <hr class="my-1" />
    @can('read cities')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('cities.list') }}"><i class="fas fa-user-group text-danger"></i>
            <span class="ms-2">İller</span></a>
    @endcan
    @can('read districts')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('districts.list') }}"><i
                class="fas fa-user-group text-danger"></i> <span
                class="ms-2">İlçeler</span></a>
    @endcan
    @can('read neighborhoods')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('neighborhoods.list') }}"><i
                class="fas fa-user-group text-danger"></i> <span
                class="ms-2">Mahalleler</span></a>
    @endcan
    @can('read localities')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('localities.list') }}"><i
                class="fas fa-user-group text-danger"></i> <span
                class="ms-2">Semtler</span></a>
    @endcan
    @can('read banks')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('banks.list') }}"><i class="fas fa-user-group text-danger"></i>
            <span class="ms-2">Bankalar</span></a>
    @endcan
    <hr class="my-1" />
    @can('read vehicle_brands')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('vehicle_brands.list') }}"><i class="fas fa-car text-danger"></i>
            <span class="ms-2">Markalar</span></a>
    @endcan
    @can('read vehicle_tickets')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('vehicle_tickets.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Tipler</span></a>
    @endcan
    @can('read vehicle_models')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('vehicle_models.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Modeller</span></a>
    @endcan
    @can('read vehicle_property_categories')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('vehicle_property_categories.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Özellik Kategorileri</span></a>
    @endcan
    @can('read vehicle_properties')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('vehicle_properties.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Özellikler</span></a>
    @endcan
    @can('read account_type_categories')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('account_type_categories.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Cari Kategorileri</span></a>
    @endcan
    @can('read account_types')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('account_types.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">Cari Seçenekleri</span></a>
    @endcan

    @can('read hgs_type_categories')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('hgs_type_categories.list') }}"><i
                class="fas fa-car text-danger"></i>
            <span class="ms-2">HGS Kategorileri</span></a>
    @endcan
    @can('read hgs_types')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('hgs_types.list') }}"><i class="fas fa-car text-danger"></i>
            <span class="ms-2">HGS Seçenekleri</span></a>
    <hr class="my-1" />
    @endcan
    
    @can('read dealer_type_categories')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('dealer_type_categories.list') }}"><i class="fas fa-shop text-danger"></i>
            <span class="ms-2">Bayi Kategorileri</span></a>
    @endcan
    @can('read dealer_type_categories')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('dealer_types.list') }}"><i class="fas fa-shop text-danger"></i>
            <span class="ms-2">Bayi Seçenekleri</span></a>
    @endcan
    @can('read dealers')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('dealers.list') }}"><i class="fas fa-shop text-danger"></i>
            <span class="ms-2">Bayiler</span></a>
    <hr class="my-1" />
    @endcan

    @can('read users')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('users.list') }}"><i
                class="fas fa-user-group text-danger"></i> <span
                class="ms-2">Personeller</span></a>
    @endcan
    <livewire:logout />
</div>