<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

/**
 * Trait for returning prepared "exist" validation rules
 *
 * @package \App\Http\Traits
 */
trait ExistValidationRules
{
    /**
     * @param string $table
     * @param string $column
     * @return Exists
     */
    protected function existByColumnRule(string $table, string $column = 'id') : Exists
    {
        return Rule::exists($table, $column);
    }

    /**
     * @return Exists
     */
    protected function userExistRule() : Exists
    {
        return $this->existByColumnRule('users');
    }

    /**
     * @param array|string|null $names
     * @return Exists
     */
    protected function roleExistRule(array|string|null $names) : Exists
    {
        $rule = $this->existByColumnRule('roles');

        if ($names) {
            $rule->whereIn('name', (array) $names);
        }

        return $rule;
    }
}
