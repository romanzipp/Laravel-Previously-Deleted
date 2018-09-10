<?php

namespace romanzipp\PreviouslyDeleted\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedAttribute extends Model
{
    /**
     * Fillable attributes
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

        $this->setTable(config('previously-deleted.table'));
    }

    /**
     * Find deleted Attribute by validation rule input.
     * @param  string $attribute Attribute
     * @param  string $table     Table name
     * @return self|null
     */
    public static function findByRule(string $attribute, string $table)
    {
        return self::query()
            ->where('table', $table)
            ->where('attribute', $attribute)
            ->first();
    }

    /**
     * Determine if attribute has been deleted previously.
     * @param  string $attribute Attribute
     * @param  string $table     Table name
     * @param  string $value     Value
     * @return boolean
     */
    public static function wasPreviouslyDeleted($attribute, $table, $value): bool
    {
        if (!$deleted = self::findByRule($attribute, $table)) {
            return false;
        }

        return $deleted->valueEquals($value);
    }

    /**
     * Compare given value against stored value
     * @param  string $value Input value
     * @return bool
     */
    public function valueEquals($value): bool
    {
        if ($this->method === null) {
            return $value == $this->value;
        }

        if (!function_exists($this->method)) {
            return false;
        }

        $value = call_user_func($this->method, $value);

        return $value == $this->value;
    }
}
