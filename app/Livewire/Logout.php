<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Logout extends Component
{
    use LivewireAlert;
    public function logout()
    {
        $this->alert('success', __('Logout successfully'), ['position' =>  'top-end']);
        Auth::logout();
        $guard = auth()->user();
        // dd($guard);
        return redirect()->route('login');
    }
    public function render()
    {
        return view('livewire.logout');
    }
}
