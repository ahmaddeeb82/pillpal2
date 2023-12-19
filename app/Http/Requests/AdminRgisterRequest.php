<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Contracts\Validation\Validator; 
use App\Exceptions\MyValidationException;

class AdminRgisterRequest extends FormRequest
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
        if(auth()->check()) {
            return [
                'first_name' => 'required',
                'last_name'=> 'required',
                'phone' => 'required|unique:users,phone,'.auth()->guard('admin')->user()->id.'|phone:INTERNATIONAL',
                'address'=> 'required',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ];
        }
        else {
            return [
                'first_name' => 'required',
                'last_name'=> 'required',
                'phone' => 'required|unique:users|phone:INTERNATIONAL',
                'address'=> 'required',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ];
        }
        
    }

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'first_name'=> 'الاسم الأول',
                'last_name'=> 'الاسم الأخير',
                'phone' => 'رقم الهاتف',
                'address'=> 'العنوان',
                'password' => 'كلمة المرور',
                'confirm_password'=> 'تأكيد كلمة المرور',
            ];
        }
        else {
            return [
                'first_name'=> 'First Name',
                'last_name'=> 'Last Name',
                'phone'=> 'Phone Number',
                'address'=> 'Address',
                'password'=> 'Password',
                'confirm_password'=> 'Password Confirm',
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'first_name.required' => 'الرجاء إدخال :attribute الخاص بك',
                'last_name.required' => 'الرجاء إدخال :attribute الخاص بك',
                'phone.unique' => ':attribute مستخدم سابقاً يرجى إدخال رقم آخر.',
                'phone.required'=> 'الرجاء إدخال :attribute الخاص بك',
                'password.required'=> 'الرجاء إدخال :attribute الخاصة بك',
                'phone.phone'=> 'الرجاء إدخال :attribute الخاص بك بطريقة صحيحة',
                'password.min'=> ':attribute الخاصة بك يجب أن تكون على الأقل :min',
                'address.required' => 'الرجاء إدخال :attribute الخاص بك',
                'confirm_password.required' => 'يرجى ملئ حقل :attribute',
                'confirm_password.same' => 'يجب أن يتطابق حقل :attribute مع :other',
            ];
        }
        else {
            return [
                'first_name.required' => 'Please Enter Your :attribute .',
                'last_name.required' => 'Please Enter Your :attribute .',
                'phone.unique' => 'The :attribute you entered has been used, please enter another one.',
                'phone.required'=> 'Please Enter Your :attribute .',
                'password.required'=> 'Please Enter Your :attribute .',
                'phone.phone'=> 'Please Enter A Valid :attribute .',
                'address.required' => 'Please Enter Your :attribute .',
                'confirm_password.required' => 'Please Enter Your :attribute .',
            ];
        }
    }
}
