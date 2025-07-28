<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\Schema;

class GetRule implements ValidationRule, DataAwareRule
{
    protected $data = [];
    protected string $model;

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $searchField = $this->data['search'] ?? null;

        if (!$searchField || !Schema::hasColumn((new $this->model)->getTable(), $searchField)) {
            return;
        }

        $exists = ($this->model)::where($searchField, $value)->exists();

        if (!$exists) {
            $fail(trans('validation.custom.get_rule.not_found'));
        }
    }
}
