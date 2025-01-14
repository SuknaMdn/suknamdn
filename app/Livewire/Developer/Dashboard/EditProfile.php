<?php

namespace App\Livewire\Developer\Dashboard;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;


#[Layout('components.layouts.developer')]
#[Title('تعديل الملف الشخصي')]
class EditProfile extends Component
{
    use LivewireAlert, WithFileUploads;
    public $user;
    public $firstname, $lastname, $email, $password, $confirmPassword, $currentPassword, $country_code, $phone, $photo;
    public $showEmailEdit = false;
    public $showPasswordEdit = false;
    public $developer;
    public $developerName, $developerEmail, $developerPhone, $developerDescription, $developerAddress, $developerLogo;

    public function toggleEmailEdit()
    {
        $this->showEmailEdit = !$this->showEmailEdit;
    }
    public function togglePasswordEdit()
    {
        $this->showPasswordEdit = !$this->showPasswordEdit;
    }
    public function mount()
    {
        $this->user = auth()->user();
        $this->firstname = $this->user->firstname;
        $this->lastname = $this->user->lastname;
        $this->phone = $this->user->phone;
        $this->country_code = $this->user->country_code;
        $this->developer = $this->user->developer;

        $this->developerName = $this->developer->name;
        $this->developerEmail = $this->developer->email;
        $this->developerPhone = $this->developer->phone;
        $this->developerDescription = $this->developer->description;
        $this->developerAddress = $this->developer->address;
        $this->developerLogo = null;
    }

    public function updateUserProfile()
    {
        $validatedData = $this->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'country_code' => 'required|string|max:5',
            'phone' => 'required|string|max:15',
        ]);

        // Update the user's profile
        $user = auth()->user();

        $user->update($validatedData);

        $this->alert('success', 'Profile updated successfully!');
    }

    public function updateEmail()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        auth()->user()->update(['email' => $this->email]);

        $this->alert('success', 'Email updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password',
        ]);

        if (!Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->alert('error', 'Current password is incorrect!');
            return;
        }

        auth()->user()->update(['password' => Hash::make($this->password)]);

        $this->alert('success', 'Password updated successfully!');
    }

    public function updateDeveloperProfile()
    {
        $validatedData = $this->validate([
            'developerLogo' => 'nullable',
            'developerLogo.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'developerName' => 'required|string|max:255',
            'developerEmail' => 'required|email|unique:developers,email,' . $this->developer->id,
            'developerPhone' => 'required|string|max:15',
            'developerDescription' => 'required|string',
            'developerAddress' => 'required|string',
        ]);

        if ($this->developerLogo != null) {
            $newLogo = $this->developerLogo->store('DeveloperLogo', 'public');
            $this->developer->update([
                'logo' => $newLogo,
            ]);
        }

        try {
            $this->developer->update([
                'name' => $this->developerName,
                'email' => $this->developerEmail,
                'phone' => $this->developerPhone,
                'description' => $this->developerDescription,
                'address' => $this->developerAddress,
            ]);

            $this->alert('success', 'Developer profile updated successfully!');
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to update developer profile: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.developer.dashboard.edit-profile', [
            'developer' => $this->developer,
        ]);
    }
}
