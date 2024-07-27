<?php

namespace App\Livewire\Brand;

use App\Services\BrandService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Brands extends Component
{
    use LivewireAlert;

    protected BrandService $brandService;

    public null|int $data_id;


    public function render()
    {
        return view('livewire.brand.brands');
    }

    #[On('delete-brand')]
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
    public function handleConfirmed(BrandService $brandService)
    {
        try {
            $brandService->delete($this->data_id);
            $msg = 'Marka silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Marka silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-BrandTable');
        }
    }
}
