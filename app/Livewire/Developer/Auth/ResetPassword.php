<?php

namespace App\Livewire\Developer\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Settings\GeneralSettings;
use Livewire\Attributes\Layout;

#[Title('Reset Password')]
#[Layout('components.layouts.auth')]
class ResetPassword extends Component
{
    use LivewireAlert;
    public $password,$password_confirmation, $token;

    #[Url]
    public $email;
    public $siteLogo;

    public function mount($token)
    {
        $this->token = $token;
        $this->siteLogo = app(GeneralSettings::class)->brand_logo;
    }
    public function save()
    {

        $this->validate([
            'token' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',

        ]);


        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation
            ],
            function (User $user, string $password) {
                $password = $this->password;
                $user->forceFill([
                    'password' => Hash::make($password)
                ])
                ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET){
            $this->alert('success', 'تم إعادة تعين كلمة المرور بنجاح');
            return redirect()->route('login');
        }
    }
    public function render()
    {
        return view('livewire.developer.auth.reset-password');
    }
}
