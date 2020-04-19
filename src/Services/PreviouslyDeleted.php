<?php

namespace romanzipp\PreviouslyDeleted\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use romanzipp\PreviouslyDeleted\Models\DeletedAttribute;

class PreviouslyDeleted
{
    /**
     * Subject.
     *
     * @var Model
     */
    protected $subject;

    /**
     * Attributes to store.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Constructor.
     *
     * @param Model $subject Subject
     */
    public function __construct(Model $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes Attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $this->normalizeAttributes($attributes);
    }

    /**
     * Save attributes.
     *
     * @return void
     */
    public function save(): void
    {
        foreach ($this->attributes as $attribute => $method) {

            $value = $this->getAttributeValue($attribute, $method);

            DeletedAttribute::create([
                'table' => $this->getTableName(),
                'attribute' => $attribute,
                'value' => $value,
                'method' => $method,
            ]);
        }
    }

    /**
     * Get parsed attribute to store in previously deleted.
     *
     * @param string $attribute Attribute name
     * @param string|null $method Used method
     * @return string
     */
    protected function getAttributeValue(string $attribute, string $method = null): string
    {
        $value = $this->subject->{$attribute};

        if ($method === null) {
            return $value;
        }

        if ( ! function_exists($method)) {
            throw new InvalidArgumentException(sprintf('Function %s does not exist', $method));
        }

        return $method($value);
    }

    /**
     * Normalize attributes array.
     *
     * @param array $attributes Input attributes array
     * @return array Normalized array
     */
    protected function normalizeAttributes(array $attributes): array
    {
        $newAttributes = [];

        foreach ($attributes as $key => $value) {

            if (is_int($key)) {
                $newAttributes[$value] = null;
            } else {
                $newAttributes[$key] = $value;
            }
        }

        return $newAttributes;
    }

    /**
     * Get subject table name.
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->subject->getTable();
    }

    /**
     * Subject has soft deletes.
     *
     * @return boolean
     */
    protected function isSoftDeleting(): bool
    {
        return array_key_exists(
            SoftDeletes::class,
            class_uses(
                get_class($this->subject)
            )
        );
    }
}
