<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{

    public $name = 'Mustafa';

    public $title = 'Başlık';

    public function render()
    {
        return view('livewire.dashboard');
    }
}
