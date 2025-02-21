<?php

namespace App\Livewire\Landlord\DealerTypeCategory;

use App\Services\Landlord\LandlordDealerTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerTypeCategories extends Component
{
    use LivewireAlert;

    protected LandlordDealerTypeCategoryService $dealerTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.dealer-type-category.dealer-type-categories');
    }

    #[On('delete-dealerTypeCategory')]
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
    public function handleConfirmed(LandlordDealerTypeCategoryService $dealerTypeCategoryService)
    {
        try {
            $dealerTypeCategoryService->delete($this->data_id);
            $msg = 'Bayi kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Bayi kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-DealerTypeCategoryTable');
        }
    }
}
