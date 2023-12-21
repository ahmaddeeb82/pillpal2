<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CompnayRequest extends FormRequest
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
            'name' => 'required',
        ];
    }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name' => 'الاسم',
            ];
        }
        else {
            return [
                'name' => 'Name',
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name.required' => 'الرجاء إدخال :attribute'
            ];
        }
        else {
            return [
                'name.required' => 'Please Enter :attribute',
            ];
        }
    }
}
