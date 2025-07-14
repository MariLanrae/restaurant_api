<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class GetRule implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $searchField = $this->data['search'] ?? null;

        if (!$searchField) {
            return;
        }

        $exists = User::where($searchField, $value)->exists();

        if (!$exists) {
            $fail('Запись с указанными параметрами не найдена.');
        }
    }
}
