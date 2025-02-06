<?php

namespace App\Livewire\Tenant\LicenceTypeCategory;

use App\Services\LicenceTypeCategoryService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class LicenceTypeCategories extends Component
{
    use LivewireAlert;

    protected LicenceTypeCategoryService $licenceTypeCategoryService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.tenant.licence-type-category.licence-type-categories');
    }

    #[On('delete-licenceTypeCategory')]
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
    public function handleConfirmed(LicenceTypeCategoryService $licenceTypeCategoryService)
    {
        try {
            $licenceTypeCategoryService->delete($this->data_id);
            $msg = 'Sürücü belgesi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Sürücü belgesi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-LicenceTypeCategoryTable');
        }
    }
}
