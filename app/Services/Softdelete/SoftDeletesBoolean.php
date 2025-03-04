<?php declare(strict_types=1);

namespace App\Services\Softdelete;
use Illuminate\Database\Eloquent\Model;


/**
 * @method string getQualifiedIsDeletedColumn()
 * @method string getIsDeletedColumn()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withTrashed()
 */

/**
 * Trait SoftDeletesBoolean
 * Adds support for soft deletes using a boolean column.
 */
trait SoftDeletesBoolean
{
    /**
     * Indicates if the model is currently force deleting.
     *
     * @var bool
     */
    protected bool $forceDeleting = false;

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletesBoolean(): void
    {
        static::addGlobalScope(new SoftDeletingBooleanScope());
    }

    /**
     * Force a hard delete on a soft deleted model.
     *
     * @return bool|null
     */
    public function forceDelete(): ?bool
    {
        $this->forceDeleting = true;
        $deleted = $this->delete();
        $this->forceDeleting = false;

        return $deleted;
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return mixed
     */
    protected function performDeleteOnModel(): mixed
    {
        if ($this->forceDeleting) {
            $this->exists = false;

            return $this->newQueryWithoutScopes()
                ->where($this->getKeyName(), $this->getKey())
                ->forceDelete();
        }

        return $this->runSoftDelete();
    }

    /**
     * Perform the actual soft delete query on this model instance.
     *
     * @return int Number of rows affected by the update query.
     */
    protected function runSoftDelete(): int
    {
        $query = $this->newQueryWithoutScopes()
            ->where($this->getKeyName(), $this->getKey());

        $time = $this->freshTimestamp();
        $columns = [$this->getIsDeletedColumn() => 1];
        $this->{$this->getIsDeletedColumn()} = 1;

        if ($this->timestamps && !is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        return $query->update($columns);
    }

    /**
     * Restore a soft-deleted model instance.
     *
     * @return bool|null
     */
    public function restore(): ?bool
    {
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getIsDeletedColumn()} = 0;
        $this->exists = true;

        $result = $this->save();
        $this->fireModelEvent('restored', false);

        return $result;
    }

    /**
     * Determine if the model instance has been soft-deleted.
     *
     * @return bool
     */
    public function trashed(): bool
    {
        return (bool)$this->{$this->getIsDeletedColumn()};
    }

    /**
     * Register a restoring model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function restoring(\Closure|string $callback): void
    {
        static::registerModelEvent('restoring', $callback);
    }

    /**
     * Register a restored model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function restored(\Closure|string $callback): void
    {
        static::registerModelEvent('restored', $callback);
    }

    /**
     * Determine if the model is currently force deleting.
     *
     * @return bool
     */
    public function isForceDeleting(): bool
    {
        return $this->forceDeleting;
    }

    /**
     * Get the name of the "is_deleted" column.
     *
     * @return string
     */
    public function getIsDeletedColumn(): string
    {
        return defined('static::IS_DELETED') ? static::IS_DELETED : 'is_deleted';
    }

    /**
     * Get the fully qualified "is_deleted" column.
     *
     * @return string
     */
    public function getQualifiedIsDeletedColumn(): string
    {
        return $this->getTable() . '.' . $this->getIsDeletedColumn();
    }
}
