<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Exceptions\MyValidationException;

class EditProfileRequest extends FormRequest
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
                'first_name' => 'required',
                'last_name'=> 'required',
                'phone' => 'required|unique:users,phone,'.auth()->user()->id.'|phone:INTERNATIONAL',
                'address'=> 'required',
            ];
    }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'first_name'=> 'الاسم الأول',
                'last_name'=> 'الاسم الأخير',
                'phone' => 'رقم الهاتف',
                'address'=> 'العنوان',
            ];
        }
        else {
            return [
                'first_name'=> 'First Name',
                'last_name'=> 'Last Name',
                'phone'=> 'Phone Number',
                'address'=> 'Address',
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
                'phone.phone'=> 'الرجاء إدخال :attribute الخاص بك بطريقة صحيحة',
                'address.required' => 'الرجاء إدخال :attribute الخاص بك',
            ];
        }
        else {
            return [
                'first_name.required' => 'Please Enter Your :attribute .',
                'last_name.required' => 'Please Enter Your :attribute .',
                'phone.unique' => 'The :attribute you entered has been used, please enter another one.',
                'phone.required'=> 'Please Enter Your :attribute .',
                'phone.phone'=> 'Please Enter A Valid :attribute .',
                'address.required' => 'Please Enter Your :attribute .',
            ];
        }
    }
}
