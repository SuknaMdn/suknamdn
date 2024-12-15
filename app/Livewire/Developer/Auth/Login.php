<?php

namespace App\Livewire\Developer\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use App\Settings\GeneralSettings;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = true;
    public $siteLogo;

    public function mount()
    {
        $this->siteLogo = app(GeneralSettings::class)->brand_logo;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            if (session()->has('url.intended')) {
                return redirect()->intended();
            } else {
                return redirect()->route('developer.dashboard');
            }
        } else {
            session()->flash('error', 'البريد الإلكتروني أو كلمة المرور غير متطابقين');
            return;
        }
    }

    #[Title('Login')]
    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.developer.auth.login', [
            'siteLogo' => $this->siteLogo,
        ]);
    }
}
