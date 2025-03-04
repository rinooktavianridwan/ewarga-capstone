<?php

namespace App\Services\Softdelete;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class SoftDeletingBooleanScope
 *
 * @package Webkid\LaravelBooleanSoftdeletes
 * @method  Model getQualifiedIsDeletedColumn()
 * @method  Model onlyTrashed()
 * @method  Model withoutTrashed()
 * @method  Model withTrashed()
 * @method bool restore()
 */
class SoftDeletingBooleanScope implements Scope
{
    protected $extensions = ['Restore', 'WithTrashed', 'WithoutTrashed', 'OnlyTrashed'];

    public function apply(Builder $builder, Model $model)
    {
        if (!in_array(SoftDeletesBoolean::class, class_uses_recursive($model))) {
            throw new \LogicException("Model must use SoftDeletesBoolean to support this operation.");
        }

        $builder->where($model->getQualifiedIsDeletedColumn(), 0);
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getIsDeletedColumn($builder);

            return $builder->update([
                $column => 1,
            ]);
        });
    }

    protected function getIsDeletedColumn(Builder $builder)
    {
        $model = $builder->getModel();

        if (!in_array(SoftDeletesBoolean::class, class_uses_recursive($model))) {
            throw new \LogicException("Model must use SoftDeletesBoolean to support this operation.");
        }

        return count((array) $builder->getQuery()->joins) > 0
            ? $model->getQualifiedIsDeletedColumn()
            : $model->getIsDeletedColumn();
    }

    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $model = $builder->getModel();

            if (!method_exists($model, 'getIsDeletedColumn')) {
                throw new \LogicException("Model must define 'getIsDeletedColumn' to use 'restore'.");
            }

            $builder->withTrashed();

            return $builder->update([$model->getIsDeletedColumn() => 0]);
        });
    }

    protected function addWithTrashed(Builder $builder)
    {
        $builder->macro('withTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            if (!method_exists($model, 'getQualifiedIsDeletedColumn')) {
                throw new \LogicException("Model must define 'getQualifiedIsDeletedColumn' to use 'withTrashed'.");
            }
            return $builder->withoutGlobalScope($this);
        });
    }

    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            if (!method_exists($model, 'getQualifiedIsDeletedColumn')) {
                throw new \LogicException("Model must define 'getQualifiedIsDeletedColumn' to use 'withoutTrashed'.");
            }

            return $builder->withoutGlobalScope($this)
                ->where($model->getQualifiedIsDeletedColumn(), 0);
        });
    }

    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            if (!method_exists($model, 'getQualifiedIsDeletedColumn')) {
                throw new \LogicException("Model must define 'getQualifiedIsDeletedColumn' to use 'onlyTrashed'.");
            }

            return $builder->withoutGlobalScope($this)
                ->where($model->getQualifiedIsDeletedColumn(), 1);
        });
    }
}
