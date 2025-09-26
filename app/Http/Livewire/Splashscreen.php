<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Cookie;
use Illuminate\Http\Response;

class Splashscreen extends Component
{
    public $verified = false;

    public function render()
    {
        return view('livewire.splashscreen');
    }

    public function getStarted($value)
    {
        $this->verified = $value;
        $response = new Response();
        $response->withCookie(cookie()->forever('verified', $this->verified));
        // dd($response);
        // $this->emit('skip', ['value' => $value]);
    }
}
