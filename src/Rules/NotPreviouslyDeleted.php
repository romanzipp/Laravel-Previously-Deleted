<?php

namespace romanzipp\PreviouslyDeleted\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\DatabaseRule;
use romanzipp\PreviouslyDeleted\Models\DeletedAttribute;

class NotPreviouslyDeleted implements Rule
{
    use DatabaseRule;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return false === DeletedAttribute::wasPreviouslyDeleted($this->column, $this->table, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return config('previously-deleted.failed_message');
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return rtrim(sprintf('not_deleted:%s,%s',
            $this->table,
            $this->column,
        ), ',');
    }
}
