<?php

namespace App\Livewire\Tenant\DealerLogo;

use App\Services\DealerLogoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerLogos extends Component
{
    use LivewireAlert;

    public null|string $data_id;
    public null|string $dealer_id = null;
    public bool $is_show = false;


    public function mount($id = null, bool $is_show)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.tenant.dealer-logo.dealer-logos');
    }

    #[On('delete-dealer-logo')]
    function delete($id)
    {
        $this->data_id = $id;
        $this->confirm(
            'Bu işlemi yapmak istediğinize emin misiniz?',
            [
                'onConfirmed' => 'handleConfirmed',
                'position' => 'center',
                'toast' => false,
                'confirmButtonText' => 'Evet',
                'cancelButtonText' => 'Hayır',
                'theme' => 'dark',
            ]
        );
    }

    #[On('handleConfirmed')]
    public function handleConfirmed(DealerLogoService $dealerLogoService)
    {
        DB::beginTransaction();
        $data = $dealerLogoService->findById($this->data_id);
        try {
            $dealerLogoService->delete($this->data_id);
            $dealerLogo = $dealerLogoService->where(['dealer_id' => $this->dealer_id])->orderBy('created_at', 'desc')->first();
            $dealerLogo->status = true;
            $dealerLogo->save();
            
            $msg = 'Bayi logosu silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();

            if (!is_null($data->filename) && Storage::exists($data->filename)) {
                Storage::delete($data->filename);
            }
        } catch (\Exception $exception) {
            $error = "Bayi logosu silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-DealerLogoTable');
        }
    }
}
