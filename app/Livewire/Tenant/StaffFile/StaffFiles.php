<?php

namespace App\Livewire\Tenant\StaffFile;

use App\Services\Tenant\StaffFileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StaffFiles extends Component
{
    use LivewireAlert;

    public null|string $staff_id = null;
    public null|string $data_id;
    public bool $is_show = false;


    public function mount($id = null, bool $is_show)
    {
        $this->staff_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.tenant.staff-file.staff-files');
    }

    #[On('delete-staff-file')]
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
    public function handleConfirmed(StaffFileService $staffFileService)
    {
        DB::beginTransaction();
        $data = $staffFileService->findById($this->data_id);
        try {
            $staffFileService->delete($this->data_id);
            $msg = 'Personel dosyası silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();

            if (!is_null($data->filename) && Storage::exists($data->filename)) {
                Storage::delete($data->filename);
            }
        } catch (\Exception $exception) {
            $error = "Personel dosyası silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-StaffFileTable');
        }
    }
}
