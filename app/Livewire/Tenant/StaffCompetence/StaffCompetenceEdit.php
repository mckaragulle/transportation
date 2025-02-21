<?php

namespace App\Livewire\Tenant\StaffCompetence;

use App\Models\Tenant\Staff;
use App\Models\Tenant\StaffType;
use App\Services\Tenant\StaffCompetenceService;
use App\Services\Tenant\StaffTypeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffCompetenceEdit extends Component
{
    use LivewireAlert;

    public null|Model $staffCompetence = null;

    public null|array $checkStaffCompetences = [];

    public null|Collection $staffTypes = null;
    public null|Staff $staff = null;
    public null|string $staff_type_category_id = null;
    public null|string $staff_type_id = null;
    public null|string $staff_id = null;
    public null|string $expiry_date_at = null;

    public bool $status = true;
    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_type_id' => ['required', 'exists:tenant.staff_types,id'],
        'expiry_date_at' => ['nullable', 'date'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_type_id.required' => 'Lütfen yetkinlik seçeneği seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir yetkinlik seçeneği seçiniz.',
        'expiry_date_at.date' => 'Lütfen geçerli bir geçerlilik tarihi seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, StaffCompetenceService $staffCompetenceService)
    {
        if ($id !== null) {
            $this->staffCompetence = $staffCompetenceService->findById($id);
            $this->staffTypes = StaffType::query()
                ->where('staff_type_category_id', $this->staffCompetence->staff_type_category_id)
                ->get(['id', 'name']);
            $this->staff_type_id = $this->staffCompetence->staff_type_id;
            $this->expiry_date_at = $this->staffCompetence->expiry_date_at ?? null;
            $this->status = $this->staffCompetence->status;
        } else {
            return $this->redirect(route('staff_competences.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.staff-competence.staff-competence-edit');
    }

    /**
     * update the exam data
     *
     * @return void
     */
    public function update()
    {
        
        $this->validate();
        DB::beginTransaction();
        try {
            $this->staffCompetence->staff_type_id = $this->staff_type_id;
            $this->staffCompetence->expiry_date_at = $this->expiry_date_at == "" ? null : $this->expiry_date_at;
            $this->staffCompetence->status = $this->status == false ? 0 : 1;
            $this->staffCompetence->save();

            $msg = 'Personel yetkinlik bilgisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel yetkinlik bilgisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
