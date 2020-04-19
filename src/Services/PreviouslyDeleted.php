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
     * @param string|null $algorithm Hashing algorithm
     * @return string
     */
    protected function getAttributeValue(string $attribute, string $algorithm = null): ?string
    {
        $value = $this->subject->{$attribute};

        if ($value === null) {
            return null;
        }

        if ($algorithm === null) {
            return $value;
        }

        if ( ! function_exists($algorithm)) {
            throw new InvalidArgumentException(sprintf('Hashing algorithm %s does not exist', $algorithm));
        }

        return $algorithm($value);
    }

    /**
     * Normalize attributes array.
     *
     * @param array $attributes Input attributes array
     * @return array Normalized array
     */
    protected function normalizeAttributes(array $attributes): array
    {
        $computedAttributes = [];

        foreach ($attributes as $key => $value) {

            if (is_int($key)) {
                $computedAttributes[$value] = null;
                continue;
            }

            // The attribute to be stored on deletion is defined with a hashing algorithm
            $computedAttributes[$key] = $value;
        }

        return $computedAttributes;
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
