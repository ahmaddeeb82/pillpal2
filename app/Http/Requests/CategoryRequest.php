<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
use App\Rules\CategoryArUnique;
use App\Rules\CategoryEnUnique;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryRequest extends FormRequest
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
        // ['required', Rule::unique('categories')->where(function($query) {return $query->where('admin_id', $this->user()->id);})]
        return [
            'name_en' => ['required', new CategoryEnUnique($this->user()->id)],
            'name_ar' => ['required', new CategoryArUnique($this->user()->id)],
            'image' => 'required|mimes:png,jpg,jpeg',
            //'image' =>'required'
        ];
    }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name_en' =>'الاسم الانكليزي',
                'name_ar' =>'الاسم العربي',
                'image' => 'الصورة',
            ];
        }
        else {
            return [
                'name_en' => ' Eglish Name',
                'name_ar' => ' Arabic Name',
                'image' => 'Image'
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name_en.required' =>'الرجاء إدخال :attribute',
                'name_ar.required' =>'الرجاء إدخال :attribute',
                'image.required' => 'الرجاء إدخال :attribute'
            ];
        }
        else {
            return [
                'name_en.required' =>'Please Enter :attribute',
                'name_ar.required' =>'Please Enter :attribute',
                'image.required' => 'Please Enter :attribute',
            ];
        }
    }
}
