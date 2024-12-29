<?php

namespace App\Livewire\StaffTypeCategory;

use App\Services\StaffTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StaffTypeCategories extends Component
{
    use LivewireAlert;

    protected StaffTypeCategoryService $staffTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.staff-type-category.staff-type-categories');
    }

    #[On('delete-staffTypeCategory')]
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
    public function handleConfirmed(StaffTypeCategoryService $staffTypeCategoryService)
    {
        try {
            $staffTypeCategoryService->delete($this->data_id);
            $msg = 'Personel kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Personel kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-StaffTypeCategoryTable');
        }
    }
}
