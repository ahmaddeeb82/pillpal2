<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Exceptions\MyValidationException;

class ReportRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }

    public function attributes(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'start_date' => 'تاريخ البداية',
                'end_date' => 'تاريخ النهاية'
            ];
        }
        else {
            return [
                'start_date' => 'Start Date',
                'end_date' => 'End Date'
            ];
        }
    }

    public function messages(): array
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                'start_date.required' => 'الرجاء إدخال :attribute',
                'end_date.required' => 'الرجاء إدخال :attribute',
                'start_date.date' => 'الرجاء إدخال :attribute بشكل مناسب',
                'end_date.date' => 'الرجاء إدخال :attribute بشكل مناسب'
            ];
        }
        else {
            return [
                'start_date.required' => 'Please Enter :attribute',
                'end_date.required' => 'Please Enter :attribute',
                'start_date.date' => 'Please Enter "attribute As A Date',
                'end_date.date' => 'Please Enter "attribute As A Date'
            ];
        }
    }
}
