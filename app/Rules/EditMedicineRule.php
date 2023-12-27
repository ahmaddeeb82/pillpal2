<?php

namespace App\Rules;

use App\Models\Medicine;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EditMedicineRule implements ValidationRule
{
    public $medicines,$medicine_id;

    public function __construct($id, $medicine_id)
    {
        $this->medicines = Medicine::where('admin_id', $id)->get();
        $this->medicine_id = $medicine_id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($this->medicines as $medicine) {
            if($value == $medicine->commercial_name) {
                if($medicine->id == $this->medicine_id) {
                    continue;
                }
                $fail("The commercial name must be unique.");
            }
        }
    }
}
