<?php

namespace App\Livewire\Landlord\AccountTypeCategory;

use App\Services\Landlord\LandlordAccountTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountTypeCategories extends Component
{
    use LivewireAlert;

    protected LandlordAccountTypeCategoryService $accountTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.account-type-category.account-type-categories');
    }

    #[On('delete-accountTypeCategory')]
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
    public function handleConfirmed(LandlordAccountTypeCategoryService $accountTypeCategoryService)
    {
        try {
            $accountTypeCategoryService->delete($this->data_id);
            $msg = 'Cari kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Cari kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-AccountTypeCategoryTable');
        }
    }
}
