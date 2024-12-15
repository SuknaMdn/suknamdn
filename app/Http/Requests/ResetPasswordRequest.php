<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ResetPasswordRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422) // Use 422 Unprocessable Entity status
        );
    }

    public function authorize()
    {
        return true; // Or add custom authorization logic
    }
    public function rules()
    {
        return [
            'new_password' => 'required|min:8|confirmed'
        ];
    }
    public function messages()
    {
        return [
            'current_password.required' => 'الرقم السري الحالي مطلوبًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            'avatar.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'avatar.max' => 'يجب ألا يتجاوز حجم الصورة 2048 كيلوبايت.',
            'password.min' => 'يجب ألا يكون الرقم السري أقل من 8 أحرف.',
            'password.confirmed' => 'يجب أن يكون الرقم السري متطابقًا مع التأكيد.',
            'password.required' => 'يجب أن يكون الرقم السري مطلوبًا.',
            'current_password.required' => 'الرقم السري الحالي مطلوبًا.',
            'new_password.required' => 'الرقم السري الجديد مطلوبًا.',
            'new_password.confirmed' => 'يجب أن يكون الرقم السري الجديد متطابقًا مع التأكيد.',
            'current_password.incorrect' => 'الرقم السري الحالي غير متطابق.',
        ];
    }
}
