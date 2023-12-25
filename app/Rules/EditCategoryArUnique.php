<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EditCategoryArUnique implements ValidationRule
{
    public $categories,$category_id;

    public function __construct($id, $category_id)
    {
        $this->categories = Category::where('admin_id', $id)->get();
        $this->category_id = $category_id;
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
                    if($category->id == $this->category_id) {
                        continue;
                    }
                    $fail("The category Arabic name must be unique.");
                }
        }
    }
}
