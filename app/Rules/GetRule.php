<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
/**
 * @method input(string $string)
 */
class GetRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $search, $search_order;


    public function __construct($search, $search_order)
    {
        $this->search = $search;
        $this->search_order = $search_order;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = User::where($this->search, $this->search_order)->exists();
        if (!$exists) {
            $fail($attribute.'Запись с указанными параметрами не найдена.');

        }
    }
}
