<?php

namespace romanzipp\PreviouslyDeleted\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $method
 * @property string $value
 * @property string $attribute
 * @property string $table
 */
class DeletedAttribute extends Model
{
    /**
     * Fillable attributes.
     *
     * @var string[]
     */
    protected $fillable = [
        'table',
        'attribute',
        'value',
        'method',
    ];

    /**
     * @param string[] $attributes
     */
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
     *
     * @return bool
     */
    public static function wasPreviouslyDeleted($attribute, $table, $value): bool
    {
        $deletedItems = self::query()
            ->where('table', $table)
            ->where('attribute', $attribute)
            ->get();

        foreach ($deletedItems as $deleted) {
            /**
             * @var DeletedAttribute $deleted
             */
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
     *
     * @return bool
     */
    public function valueEquals($value): bool
    {
        if (null === $this->method) {
            return $value == $this->value;
        }

        if ( ! in_array($this->method, hash_algos(), false)) {
            throw new \InvalidArgumentException(sprintf('Hashing algorithm "%s" is not available', $this->method));
        }

        return hash($this->method, $value) == $this->value;
    }
}
