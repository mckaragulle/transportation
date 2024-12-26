<li>
    <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
        <i class="fas fa-cogs "></i>
        <span class="nav-text">AYARLAR</span>
    </a>
    <ul aria-expanded="false" class="mm-collapse left" style="">

        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-file-invoice"></i>
                <span class="ms-2">CARİLER</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read accounts')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('accounts.list') }}"><i
                                class="fas fa-users text-danger"></i>
                            <span class="ms-2">Cariler</span></a>
                    </li>
                @endcan
            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-id-card"></i>
                <span class="ms-2">SÜRÜCÜ BELGELERİ</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read licences')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('licences.list') }}"><i
                                class="fa-solid fa-id-card text-danger"></i>
                            <span class="ms-2">Sürücü Belgeleri</span></a>
                    </li>
                @endcan
            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fas fa-ticket "></i>
                <span class="ms-2">HGS</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read hgses')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('hgses.list') }}"><i
                                class="fas fa-ticket text-danger"></i>
                            <span class="ms-2">HGS'ler</span></a>
                    </li>
                @endcan

            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-users-gear "></i>
                <span class="ms-2">Personel</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read staffs')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('staffs.list') }}"><i
                                class="fa-solid fa-users-gear text-danger"></i>
                            <span class="ms-2">Personeller</span></a>
                    </li>
                @endcan
            </ul>
        </li>
    </ul>
</li>
@can('read fineds')
    <li>
        <a href="{{ route('fineds.list') }}" aria-expanded="false">
            <i class="fas  fa-ticket"></i>
            <span class="nav-text">ARAÇ CEZALARI</span>
        </a>
    </li>
@endcan
<li>
    @php 
    $id = auth()->user()->id;
    @endphp
    <a href="{{ route('dealer_managements.edit', ['id' => $id]) }}" aria-expanded="false">
        <i class="fas fa-ticket"></i>
        <span class="nav-text">BAYİ YÖNETİMİ</span>
    </a>
</li>