<div class="dropdown-menu dropdown-menu-end"> 
    @can('read users')
        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
            href="{{ route('users.list') }}"><i
                class="fas fa-user-group text-danger"></i> <span
                class="ms-2">Personeller</span></a>
    @endcan
    <livewire:logout />
</div>