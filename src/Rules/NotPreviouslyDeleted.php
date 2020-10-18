<?php

namespace romanzipp\PreviouslyDeleted\Rules;

use InvalidArgumentException;
use romanzipp\PreviouslyDeleted\Models\DeletedAttribute;

class NotPreviouslyDeleted
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $name
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validate(string $name, $value, array $parameters): bool
    {
        if (count($parameters) < 1) {
            throw new InvalidArgumentException('The parameters Attribute must have at least 1 element');
        }

        $table = $parameters[0];

        $attribute = 2 === count($parameters) ? $parameters[1] : $name;

        return false === DeletedAttribute::wasPreviouslyDeleted($attribute, $table, $value);
    }

    /**
     * Get the validation error message.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function message($message, $attribute, $rule, $parameters): string
    {
        return str_replace(
            ':attribute',
            $attribute,
            config('previously-deleted.failed_message')
        );
    }
}
