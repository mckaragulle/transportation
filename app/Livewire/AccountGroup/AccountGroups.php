<?php

namespace App\Livewire\AccountGroup;

use App\Services\AccountGroupService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountGroups extends Component
{
    use LivewireAlert;

    public null|int $data_id;


    public function render()
    {
        return view('livewire.account-group.account-groups');
    }

    #[On('delete-account-group')]
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
    public function handleConfirmed(AccountGroupService $accountGroupService)
    {
        DB::beginTransaction();
        $data = $accountGroupService->findById($this->data_id);
        try {
            $accountGroupService->delete($this->data_id);
            $msg = 'Cari grubu silindi.';
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
            $error = "Cari grubu silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        } finally {
            $this->dispatch('pg:eventRefresh-AccountGroupTable');
        }
    }
}
