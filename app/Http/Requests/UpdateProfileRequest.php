<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422) // Use 422 Unprocessable Entity status
        );
    }

    // Optionally, authorize method
    public function authorize()
    {
        return true; // Or add custom authorization logic
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $userId = auth()->id();

        return [
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('users')->ignore($userId)
            ],
            'avatar' => 'sometimes|image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'firstname.string' => 'يجب أن يكون الاسم الأول نصًا.',
            'firstname.max' => 'يجب ألا يتجاوز الاسم الأول 255 حرفًا.',
            'lastname.string' => 'يجب أن يكون اسم العائلة نصًا.',
            'lastname.max' => 'يجب ألا يتجاوز اسم العائلة 255 حرفًا.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني عنوان بريد إلكتروني صالحًا.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 20 حرفًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            'avatar.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'avatar.max' => 'يجب ألا يتجاوز حجم الصورة 2048 كيلوبايت.',
        ];
    }
}
