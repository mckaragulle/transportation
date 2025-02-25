<?php

namespace App\Livewire\Landlord;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

class AdminSignin extends Component
{
    use LivewireAlert;

    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|string|email',
        'password' => 'required|string|min:6',
    ];

    protected $messages = [
        'email.required' => 'Eposta adresinizi giriniz.',
        'email.email' => 'Geçerli bir eposta adresi giriniz.',
        'password.required' => 'Şifrenizi giriniz.',
        'password.min' => 'Şifreniz en az 6 haneli olmalıdır.',
    ];

    public function submit()
    {

        $credentials = $this->validate();

        if (Auth::guard('admin')->attempt($credentials)) {
            session()->flash('message', 'Giriş yaptınız.');

            return redirect()->route('yonetim');
        }
        else {
            $error = 'Giriş yapılamadı.';
            $this->alert('error', $error);
            Log::error($error);
            session()->flash('error', 'Eposta adresiniz veya şifreniz hatalı tekrar deneyiniz.');
        }
    }

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.landlord.admin-signin');
    }
}
