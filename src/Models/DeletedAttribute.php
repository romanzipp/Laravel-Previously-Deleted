<?php

namespace romanzipp\PreviouslyDeleted\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedAttribute extends Model
{
    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'table',
        'attribute',
        'value',
        'method',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(
            config('previously-deleted.table')
        );
    }

    /**
     * Determine if attribute has been deleted previously.
     *
     * @param string $attribute Attribute
     * @param string $table Table name
     * @param string $value Value
     * @return boolean
     */
    public static function wasPreviouslyDeleted($attribute, $table, $value): bool
    {
        $deletedItems = self::query()
            ->where('table', $table)
            ->where('attribute', $attribute)
            ->get();

        foreach ($deletedItems as $deleted) {

            if ($deleted->valueEquals($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compare given value against stored value.
     *
     * @param string $value Input value
     * @return bool
     */
    public function valueEquals($value): bool
    {
        if ($this->method === null) {
            return $value == $this->value;
        }

        if ( ! function_exists($this->method)) {
            return false;
        }

        $value = call_user_func($this->method, $value);

        return $value == $this->value;
    }
}
