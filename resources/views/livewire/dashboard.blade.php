<div>
    @if(auth()->getDefaultDriver() == 'admin')
    <button type="button" class="btn btn-primary" wire:click="importBrand">Markalar İçe Aktar</button>
    <button type="button" class="btn btn-secondary" wire:click="importCity">Şehirler İçe Aktar</button>
    <button type="button" class="btn btn-blue" wire:click="importBank">Bankalar İçe Aktar</button>
    @endif

    {{-- @if(auth()->getDefaultDriver() == 'admin')
    <livewire:dealer.dealer-management id="{{auth()->user()->id}}">
    @endif --}}
</div>