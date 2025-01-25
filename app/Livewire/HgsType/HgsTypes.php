<?php

namespace App\Livewire\HgsType;

use App\Services\HgsTypeService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class HgsTypes extends Component
{
    use LivewireAlert;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.hgs-type.hgs-types');
    }

    #[On('delete-hgsType')]
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
    public function handleConfirmed(HgsTypeService $hgsTypeService)
    {
        try {
            $hgsTypeService->delete($this->data_id);
            $msg = 'Özellik silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Özellik silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-HgsTypeTable');
        }
    }
}
