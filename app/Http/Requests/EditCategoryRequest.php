<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
use App\Rules\CategoryArUnique;
use App\Rules\EditCategoryArUnique;
use App\Rules\EditCategoryEnUnique;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EditCategoryRequest extends FormRequest
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
            'name_en' => ['required', new EditCategoryEnUnique($this->user()->id, $this->category_id)],
            'name_ar' => ['required', new EditCategoryArUnique($this->user()->id, $this->category_id)],
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
