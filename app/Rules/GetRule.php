<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use function Laravel\Prompts\error;

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
        $val = DB::table('users')->where($this->search, $this->search_order)->exists();
        if (!$val){
            abort(404, "Запись не найдена");
        }
    }
}
