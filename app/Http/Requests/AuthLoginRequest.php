<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Contracts\Validation\Validator; 
use App\Exceptions\MyValidationException;



class AuthLoginRequest extends FormRequest
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
            'phone' => 'required|phone:INTERNATIONAL',
            'password' => 'required|min:6',
            'device_token' => 'required'
        ];
    }

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'phone' => 'رقم الهاتف',
                'password' => 'كلمة المرور',
                'device_token' => 'توكن الجهاز'

            ];
        }
        else {
            return [
                'phone'=> 'Phone Number',
                'password'=> 'Password',
                'device_token' => 'Device Token'

            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'phone.required'=> 'الرجاء إدخال :attribute الخاص بك',
                'password.required'=> 'الرجاء إدخال :attribute الخاصة بك',
                'phone.phone'=> 'الرجاء إدخال :attribute الخاص بك بطريقة صحيحة',
                'password.min'=> ':attribute الخاصة بك يجب أن تكون على الأقل :min',
                'device_token.required' => 'الرجاء إدخال :attribute الخاص بك'
            ];
        }
        else {
            return [
                'phone.required'=> 'Please Enter Your :attribute .',
                'password.required'=> 'Please Enter Your :attribute .',
                'phone.phone'=> 'Please Enter A Valid :attribute .',
                'device_token.required' => 'Please Enter Your :attribute .'
            ];
        }
    }

    
}
