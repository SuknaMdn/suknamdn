<?php

namespace App\Livewire\Developer\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Settings\GeneralSettings;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('Forgot Password')]
#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    use LivewireAlert;
    public $email;
    public $siteLogo;

    public function mount()
    {
        $this->siteLogo = app(GeneralSettings::class)->brand_logo;
    }
    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        if ($this->email == null) {
            $this->alert('error', 'البريد الإلكتروني مطلوب');
            return;
        }
        $status = Password::sendResetLink(['email' => $this->email]);

        if($status === Password::RESET_LINK_SENT){
            $this->alert('success', 'تم إرسال رابط إعادة تعين كلمة المرور إلى بريدك الإلكتروني');
            $this->reset('email');
        }else{
            $this->alert('error', 'لا يمكن إرسال رابط إعادة تعين كلمة المرور إلى بريدك الإلكتروني');
        }
    }
    public function render()
    {
        return view('livewire.developer.auth.forgot-password' , [
            'siteLogo' => $this->siteLogo
        ]);
    }
}
