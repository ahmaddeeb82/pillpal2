<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryArUnique implements ValidationRule
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
            if($value == $category->getTranslations('name')['ar']) {
                $fail("The category Arabic name must be unique.");
            }
        }
    }
}
