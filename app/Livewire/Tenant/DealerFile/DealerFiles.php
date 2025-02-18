<?php

namespace App\Livewire\Tenant\DealerFile;

use App\Services\DealerFileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerFiles extends Component
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
        return view('livewire.tenant.dealer-file.dealer-files');
    }

    #[On('delete-dealer-file')]
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
    public function handleConfirmed(DealerFileService $dealerFileService)
    {
        DB::beginTransaction();
        $data = $dealerFileService->findById($this->data_id);
        try {
            $dealerFileService->delete($this->data_id);
            $msg = 'Bayi dosyası silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();

            if (!is_null($data->filename) && Storage::exists($data->filename)) {
                Storage::delete($data->filename);
            }
        } catch (\Exception $exception) {
            $error = "Bayi dosyası silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-DealerFileTable');
        }
    }
}
