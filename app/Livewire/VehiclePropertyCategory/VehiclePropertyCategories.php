<?php

namespace App\Livewire\VehiclePropertyCategory;

use App\Services\VehiclePropertyCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class VehiclePropertyCategories extends Component
{
    use LivewireAlert;

    protected VehiclePropertyCategoryService $VehiclePropertyCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.vehicle-property-category.vehicle-property-categories');
    }

    #[On('delete-vehiclePropertyCategory')]
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
    public function handleConfirmed(VehiclePropertyCategoryService $VehiclePropertyCategoryService)
    {
        try {
            $VehiclePropertyCategoryService->delete($this->data_id);
            $msg = 'Araç modül kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Araç modül kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-VehiclePropertyCategoryTable');
        }
    }
}
