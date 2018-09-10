<?php

namespace romanzipp\PreviouslyDeleted\Rules;

use romanzipp\PreviouslyDeleted\Models\DeletedAttribute;

class NotPreviouslyDeleted
{
    /**
     * Determine if the validation rule passes.
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validate($name, $value, $parameters)
    {
        if (!is_array($parameters)) {
            throw new \InvalidArgumentException('Method expects parameters to be array');
        }

        if (count($parameters) < 1) {
            throw new \InvalidArgumentException('parameters Attribute must have at least 1 element');
        }

        $table = $parameters[0];

        $attribute = count($parameters) == 2 ? $parameters[1] : $name;

        return DeletedAttribute::wasPreviouslyDeleted($attribute, $table, $value) == false;
    }

    /**
     * Get the validation error message.
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    public function message($message, $attribute, $rule, $parameters)
    {
        return str_replace(':attribute', $attribute, config('previously-deleted.failed_message'));
    }
}
