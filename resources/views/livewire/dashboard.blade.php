<div>
    @if(auth()->getDefaultDriver() == 'admin')
    <button type="button" class="btn btn-primary" wire:click="importBrand">Markalar İçe Aktar</button>
    <button type="button" class="btn btn-secondary" wire:click="importCity">Şehirler İçe Aktar</button>
    <button type="button" class="btn btn-blue" wire:click="importBank">Bankalar İçe Aktar</button>
    @endif

    {{-- @if(count(auth()->user()->getAllPermissions()) > 0)
    @foreach (auth()->user()->getAllPermissions() as $p)
        <h6>{{$p->name}}</h6>
    @endforeach
    @endif --}}
    {{-- @if(auth()->getDefaultDriver() == 'admin')
    <livewire:dealer.dealer-management id="{{auth()->user()->id}}">
    @endif --}}
</div>