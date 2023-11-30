<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Exceptions\MyValidationException;

class PasswordRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */

    protected function failedValidation(Validator $validator) {
        throw new MyValidationException($validator);
    }

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
                'old_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ];
        }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'old_password'=> 'كلمة المرور القديمة',
                'new_password' => 'كلمة المرور الجديدة',
                'confirm_password'=> 'تأكيد كلمة المرور',
            ];
        }
        else {
            return [
                'old_password'=> 'Old Password',
                'new_password' => 'New Password',
                'confirm_password'=> 'Password Confirm',
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'old_password.required' => 'الرجاء إدخال :attribute الخاص بك',
                'new_password.required'=> 'الرجاء إدخال :attribute الخاصة بك',
                'new_password.min'=> ':attribute الخاصة بك يجب أن تكون على الأقل :min',
                'confirm_password.required' => 'يرجى ملئ حقل :attribute',
                'confirm_password.same' => 'يجب أن يتطابق حقل :attribute مع :other',
            ];
        }
        else {
            return [
                'old_password.required' => 'Please Enter Your :attribute .',
                'new_password.required'=> 'Please Enter Your :attribute .',
                'confirm_password.required' => 'Please Enter Your :attribute .',
            ];
        }
    }
}
