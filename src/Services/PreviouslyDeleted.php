<?php

namespace romanzipp\PreviouslyDeleted\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
     * @var array<string, string|null>
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
     * @param array<string, string|null> $attributes Attributes
     *
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
        foreach ($this->attributes as $attribute => $algorithm) {
            $value = $this->getAttributeValue($attribute, $algorithm);

            if (null === $value) {
                continue;
            }

            DeletedAttribute::query()->create([
                'table' => $this->getTableName(),
                'attribute' => $attribute,
                'value' => $value,
                'method' => $algorithm,
            ]);
        }
    }

    /**
     * Determine if attributes should be store for the given model.
     * This method also takes soft deletion in consideration.
     *
     * @return bool
     */
    public function shouldStoreAttributes(): bool
    {
        // Store attributes if the model is not using soft deletion
        if ( ! method_exists($this->subject, 'isForceDeleting')) {
            return true;
        }

        // Store attributes if a soft deleting model is being forced to deletion
        if ($this->subject->isForceDeleting()) {
            return true;
        }

        return false === config('previously-deleted.ignore_soft_deleted');
    }

    /**
     * Get parsed attribute to store in previously deleted.
     *
     * @param string $attribute Attribute name
     * @param string|null $algorithm Hashing algorithm
     *
     * @return string
     */
    protected function getAttributeValue(string $attribute, string $algorithm = null): ?string
    {
        $value = $this->subject->{$attribute};

        if (null === $value) {
            return null;
        }

        if (null === $algorithm) {
            return $value;
        }

        if ( ! in_array($algorithm, hash_algos(), false)) {
            throw new \InvalidArgumentException(sprintf('Hashing algorithm "%s" is not available', $algorithm));
        }

        return hash($algorithm, $value);
    }

    /**
     * Normalize attributes array.
     *
     * @param array<string|int, string|null> $attributes Input attributes array
     *
     * @return array<string, string|null> Normalized array
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
     * @return bool
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
