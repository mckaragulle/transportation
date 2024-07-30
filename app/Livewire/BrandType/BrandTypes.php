<?php

namespace App\Livewire\BrandType;

use App\Services\BrandTypeService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class BrandTypes extends Component
{
    use LivewireAlert;

    protected BrandTypeService $brandTypeService;

    public null|int $data_id;


    public function render()
    {
        return view('livewire.brand-type.brand-types');
    }

    #[On('delete-brandType')]
    function delete($id)
    {
        $this->data_id = $id;
        $this->confirm('Bu işlemi yapmak istediğinize emin misiniz?',
        [
            'onConfirmed' => 'handleConfirmed',
            'position' => 'center',
            'toast' => false,
            'confirmButtonText' => 'Evet',
            'cancelButtonText' => 'Hayır',
            'theme' => 'dark',
        ]);
    }

    #[On('handleConfirmed')]
    public function handleConfirmed(BrandTypeService $brandTypeService)
    {
        try {
            $brandTypeService->delete($this->data_id);
            $msg = 'Marka Tipi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Marka Tipi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-BrandTypeTable');
        }
    }
}
