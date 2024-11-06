<?php

namespace App\Livewire\AccountOfficer;

use App\Services\AccountOfficerService;
use App\Services\AccountService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountOfficers extends Component
{
    use LivewireAlert;

    public null|int $data_id;


    public function render()
    {
        return view('livewire.account-officer.account-officers');
    }

    #[On('delete-account-officer')]
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
    public function handleConfirmed(AccountOfficerService $accountOfficerService)
    {
        DB::beginTransaction();
        $data = $accountOfficerService->findById($this->data_id);
        try {
            $accountOfficerService->delete($this->data_id);
            $msg = 'Cari yetkilisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();

            if(!is_null($data->files) && count($data->files) > 0){
                foreach($data->files as $file){
                    if(Storage::exists($file)){
                        Storage::delete($file);
                    }
                }
            }
        } catch (\Exception $exception) {
            $error = "Cari yetkilisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-AccountOfficerTable');
        }
    }
}