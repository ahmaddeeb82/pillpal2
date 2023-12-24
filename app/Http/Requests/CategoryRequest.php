<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
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
            'name'=>[
                 'en' => 'required', 
                 'ar' => 'required',],
            //'image' => 'required|mimes:png,jpg,jpeg',
            'image' =>'required'
        ];
    }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name' =>'الاسم',
                'image' => 'الصورة',
            ];
        }
        else {
            return [
                'name' => ' name en',
                'image' => 'Image'
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'name.required' =>'الرجاء إدخال :attribute',
                'image.required' => 'الرجاء إدخال :attribute'
            ];
        }
        else {
            return [
                'name.required' =>'Please Enter :attribute',
                'image.required' => 'Please Enter :attribute',
            ];
        }
    }
}
