<?php

namespace App\Livewire\Landlord\BranchTypeCategory;

use App\Services\Landlord\LandlordBranchTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class BranchTypeCategories extends Component
{
    use LivewireAlert;

    protected LandlordBranchTypeCategoryService $branchTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.branch-type-category.branch-type-categories');
    }

    #[On('delete-branchTypeCategory')]
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
    public function handleConfirmed(LandlordBranchTypeCategoryService $branchTypeCategoryService)
    {
        try {
            $branchTypeCategoryService->delete($this->data_id);
            $msg = 'Şube kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Şube kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-BranchTypeCategoryTable');
        }
    }
}
