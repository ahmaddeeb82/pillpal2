<?php

namespace App\Http\Requests;

use App\Exceptions\MyValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ImageRequest extends FormRequest
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
            'image' => 'required|mimes:png,jpg,jpeg'
        ];
    }

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'image' => 'الصورة',
            ];
        }
        else {
            return [
                'image' => 'Image',
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'image.required' => 'الرجاء إدخال :attribute الخاصة بك',
                'image.mimes' => 'الرجاء إدخال لاحقة مناسبة لل:attribute التي تحاول إدخالها'
            ];
        }
        else {
            return [
                'image.required' => 'Please Enter Your :attribute',
                'image.mimes' => 'Please Insert An :attribute With A Valid Type'
            ];
        }
    }
}
