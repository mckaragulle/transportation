<?php

namespace App\Livewire\Landlord\HgsTypeCategory;

use App\Services\HgsTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class HgsTypeCategories extends Component
{
    use LivewireAlert;

    protected HgsTypeCategoryService $hgsTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.hgs-type-category.hgs-type-categories');
    }

    #[On('delete-hgsTypeCategory')]
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
    public function handleConfirmed(HgsTypeCategoryService $hgsTypeCategoryService)
    {
        try {
            $hgsTypeCategoryService->delete($this->data_id);
            $msg = 'Hgs kategorisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Hgs kategorisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-HgsTypeCategoryTable');
        }
    }
}
