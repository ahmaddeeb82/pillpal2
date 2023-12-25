<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryEnUnique implements ValidationRule
{
    public $categories;

    public function __construct($id)
    {
        $this->categories = Category::where('admin_id', $id)->get();
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($this->categories as $category) {
            //dd($category->getTranslations('name'));
            if($value == $category->getTranslations('name')['en']) {
                $fail("The category English name must be unique.");
            }
        }
    }
}
