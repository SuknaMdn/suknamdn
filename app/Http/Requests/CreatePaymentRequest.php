<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3|in:SAR,USD',
            'payment_method' => 'required|string|in:creditcard,stc_pay,apple_pay',
            'number' => 'required_if:payment_method,creditcard|nullable|string|digits_between:13,19',
            'name' => 'required_if:payment_method,creditcard|nullable|string|max:255',
            'month' => 'required_if:payment_method,creditcard|nullable|numeric|min:1|max:12',
            'year' => 'required_if:payment_method,creditcard|nullable|numeric|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'cvc' => 'required_if:payment_method,creditcard|nullable|string|digits_between:3,4',
            'description' => 'nullable|string|max:1000',
            'unit_id' => 'required|exists:units,id',
            'mobile' => 'required_if:payment_method,stc_pay|nullable|string|regex:/^\+9665[0-9]{8}$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
    */
    public function messages()
    {
        return [
            'amount.required' => 'المبلغ مطلوب.',
            'amount.numeric' => 'يجب أن يكون المبلغ رقمًا.',
            'amount.min' => 'يجب أن يكون المبلغ على الأقل 0.01.',
            'currency.required' => 'العملة مطلوبة.',
            'currency.string' => 'يجب أن تكون العملة نصًا.',
            'currency.size' => 'يجب أن تكون العملة مكونة من 3 أحرف بالضبط.',
            'currency.in' => 'العملة المختارة غير صالحة.',
            'payment_method.required' => 'طريقة الدفع مطلوبة.',
            'payment_method.string' => 'يجب أن تكون طريقة الدفع نصًا.',
            'payment_method.in' => 'طريقة الدفع المختارة غير صالحة.',
            'number.required_if' => 'رقم البطاقة مطلوب عند اختيار طريقة الدفع ببطاقة الائتمان.',
            'number.string' => 'يجب أن يكون رقم البطاقة نصًا.',
            'number.digits_between' => 'يجب أن يكون رقم البطاقة بين 13 و 19 رقمًا.',
            'name.required_if' => 'اسم حامل البطاقة مطلوب عند اختيار طريقة الدفع ببطاقة الائتمان.',
            'name.string' => 'يجب أن يكون اسم حامل البطاقة نصًا.',
            'name.max' => 'يجب ألا يزيد اسم حامل البطاقة عن 255 حرفًا.',
            'month.required_if' => 'شهر انتهاء الصلاحية مطلوب عند اختيار طريقة الدفع ببطاقة الائتمان.',
            'month.numeric' => 'يجب أن يكون شهر انتهاء الصلاحية رقمًا.',
            'month.min' => 'يجب أن يكون شهر انتهاء الصلاحية على الأقل 1.',
            'month.max' => 'يجب ألا يزيد شهر انتهاء الصلاحية عن 12.',
            'year.required_if' => 'سنة انتهاء الصلاحية مطلوبة عند اختيار طريقة الدفع ببطاقة الائتمان.',
            'year.numeric' => 'يجب أن تكون سنة انتهاء الصلاحية رقمًا.',
            'year.min' => 'يجب أن تكون سنة انتهاء الصلاحية على الأقل السنة الحالية.',
            'year.max' => 'يجب ألا تزيد سنة انتهاء الصلاحية عن 10 سنوات من الآن.',
            'cvc.required_if' => 'رمز التحقق من البطاقة (CVC) مطلوب عند اختيار طريقة الدفع ببطاقة الائتمان.',
            'cvc.string' => 'يجب أن يكون رمز التحقق من البطاقة (CVC) نصًا.',
            'cvc.digits_between' => 'يجب أن يكون رمز التحقق من البطاقة (CVC) بين 3 و 4 أرقام.',
            'description.string' => 'يجب أن تكون الوصف نصًا.',
            'description.max' => 'يجب ألا يزيد الوصف عن 1000 حرف.',
            'unit_id.required' => 'معرف الوحدة مطلوب.',
            'unit_id.exists' => 'معرف الوحدة المختار غير صالح.',
            'mobile.required_if' => 'رقم الجوال مطلوب عند اختيار طريقة الدفع بـ STC Pay.',
            'mobile.string' => 'يجب أن يكون رقم الجوال نصًا.',
            'mobile.regex' => 'تنسيق رقم الجوال غير صالح.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
