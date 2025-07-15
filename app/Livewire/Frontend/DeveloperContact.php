<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\User;
use App\Models\Developer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DeveloperContact extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company_name = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'company_name' => 'required|string|max:255|unique:developers,name',
        ];
    }

    public function submit()
    {
        $ip = request()->ip();
        $key = 'developer_contact_ip_' . $ip;

        if (Cache::has($key)) {
            session()->flash('error', 'لقد قمت بتقديم طلب مؤخراً. يرجى الانتظار بضع دقائق قبل المحاولة مرة أخرى.');
            return;
        }

        $validatedData = $this->validate();

        $autoPassword = Str::random(16);

        $nameParts = explode(' ', $validatedData['name'], 2);
        $firstname = $nameParts[0];
        $lastname = $nameParts[1] ?? '';

        $user = User::create([
            'id' => Str::uuid(),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($autoPassword),
        ]);

        Developer::create([
            'user_id' => $user->id,
            'name' => $validatedData['company_name'],
            'slug' => Str::slug($validatedData['company_name'] . '-' . uniqid()),
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'description' => 'New developer application.',
            'is_active' => false,
        ]);

        Cache::put($key, true, now()->addMinutes(10));

        session()->flash('success', 'تم استلام طلبك بنجاح. سنتواصل معك قريباً لمراجعة التفاصيل.');
        
        $this->reset();
    }

    public function render()
    {
        return view('livewire.frontend.developer-contact');
    }
}