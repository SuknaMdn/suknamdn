<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeleteAccountForm extends Component
{
    public $phone;
    public $successMessage = null;
    public $errorMessage = null;

    public function deleteAccount()
    {
        $this->validate([
            'phone' => 'required|string|min:8',
        ]);

        $user = User::where('phone', $this->phone)->first();

        if (!$user) {
            $this->errorMessage = 'لا يوجد مستخدم بهذا الرقم';
            return;
        }

        try {
            $user->delete(); // أو soft delete
            $this->successMessage = 'تم حذف الحساب بنجاح';
            $this->phone = '';
        } catch (\Exception $e) {
            Log::error('فشل حذف الحساب عبر Livewire', ['error' => $e->getMessage()]);
            $this->errorMessage = 'حدث خطأ أثناء حذف الحساب';
        }
    }
    public function render()
    {
        return view('livewire.frontend.auth.delete-account-form');
    }
}
