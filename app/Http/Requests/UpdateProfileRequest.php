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
            'avatar' => 'sometimes|image|max:2048',

            'addresses' => 'sometimes|array',
            'addresses.*.id' => 'sometimes|exists:addresses,id',
            'addresses.*.city_id' => 'required|max:255',
            'addresses.*.state_id' => 'required|max:255',
            'addresses.*.postal_code' => 'required|string|max:20',
            'addresses.*.country' => 'required|string|max:255',
            'addresses.*.is_default' => 'sometimes|boolean',
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

            // Address validation messages
            'addresses.array' => 'يجب أن تكون العناوين على شكل مصفوفة.',
            'addresses.*.id.exists' => 'معرف العنوان غير موجود.',
            'addresses.*.city_id.required' => 'المدينة مطلوبة.',
            'addresses.*.state_id.required' => 'المنطقة/المحافظة مطلوبة.',
            'addresses.*.postal_code.required' => 'الرمز البريدي مطلوب.',
            'addresses.*.postal_code.string' => 'يجب أن يكون الرمز البريدي نصًا.',
            'addresses.*.postal_code.max' => 'يجب ألا يتجاوز الرمز البريدي 20 حرفًا.',
            'addresses.*.country.required' => 'الدولة مطلوبة.',
            'addresses.*.country.string' => 'يجب أن تكون الدولة نصًا.',
            'addresses.*.country.max' => 'يجب ألا يتجاوز اسم الدولة 255 حرفًا.',
            'addresses.*.is_default.boolean' => 'يجب أن تكون قيمة العنوان الافتراضي صح أو خطأ.',
        ];
    }
}
