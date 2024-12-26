<?php

namespace App\Livewire\DealerOfficer;

use App\Services\DealerOfficerService;
use App\Services\DealerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerOfficers extends Component
{
    use LivewireAlert;

    public null|string $dealer_id = null;
    public null|int $data_id;
    public bool $is_show = false;

    public function mount($id = null, bool $is_show)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.dealer-officer.dealer-officers');
    }

    #[On('delete-dealer-officer')]
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
    public function handleConfirmed(DealerOfficerService $dealerOfficerService)
    {
        DB::beginTransaction();
        $data = $dealerOfficerService->findById($this->data_id);
        try {
            $dealerOfficerService->delete($this->data_id);
            $msg = 'Bayi yetkilisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();

            if (!is_null($data->files) && count($data->files) > 0) {
                foreach ($data->files as $file) {
                    if (Storage::exists($file)) {
                        Storage::delete($file);
                    }
                }
            }
        } catch (\Exception $exception) {
            $error = "Bayi yetkilisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-DealerOfficerTable');
        }
    }
}
