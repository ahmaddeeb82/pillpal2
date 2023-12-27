<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
use App\Rules\EditMedicineRule;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EditMedicineRequest extends FormRequest
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
            'scientific_name' => 'required',
            'commercial_name' => ['required', new EditMedicineRule(auth()->guard('admin')->user()->id, $this->medicine_id)],
            'quantity' => 'required',
            'price' => 'required',
            'expiration_date' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'company_id' => 'required',
            'category_id' => 'required'
        ];
    }
        

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'scientific_name' =>'الاسم العلمي',
                'commercial_name' =>'الاسم التجاري',
                'quantity' => 'الكمية',
                'price' => 'السعر',
                'expiration_date' => 'تاريخ انتهاء الصلاحية ',               
                'image' => 'الصورة',
                'company_id' => 'الشركة',
                'category_id' => 'التصنيف'
            ];
        }
        else {
            return [
                'scientific_name' => 'Scientific name',
                'commercial_name' => 'Commercial name',
                'quantity' => 'Quantity',
                'price' => 'Price',
                'expiration_date' => 'Expiration date',
                'image' => 'Image',
                'company_id' => 'Company',
                'category_id' => 'Category'
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'scientific_name.required' =>'الرجاء إدخال :attribute',
                'commercial_name.required' =>'الرجاء إدخال :attribute',
                'quantity.required' =>'الرجاء إدخال :attribute',
                'price.required' =>'الرجاء إدخال :attribute',
                'expiration_date.required' =>'الرجاء إدخال :attribute',
                'image.required' => 'الرجاء إدخال :attribute',
                'company_id.required' => 'الرجاء إدخال :attribute',
                'category_id.required' => 'الرجاء إدخال :attribute',
            ];
        }
        else {
            return [
                'scientific_name.required' =>'Please Enter :attribute',
                'commercial_name.required' =>'Please Enter :attribute',
                'quantity.required' =>'Please Enter :attribute',
                'price.required' =>'Please Enter :attribute',
                'expiration_date.required' =>'Please Enter :attribute',
                'image.required' => 'Please Enter :attribute',
                'company_id.required' => 'Please Enter :attribute',
                'category_id.required' => 'Please Enter :attribute',
            ];
        }
    }
}
