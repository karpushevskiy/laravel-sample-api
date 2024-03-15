<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for using composite primary key in Model
 *
 * @package \App\Models\Traits
 */
trait HasCompositePrimaryKey
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing() : bool
    {
        return false;
    }

    /**
     * Set the keys for a save/update query.
     *
     * @param Builder $query
     *
     * @return Builder
     * @throws Exception
     */
    protected function setKeysForSaveQuery($query) : Builder
    {
        foreach ($this->getKeyName() as $key) {
            if (!isset($this->$key)) {
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }

            $query->where($key, '=', $this->$key);
        }

        return $query;
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param array $ids Array of keys, like [column => value].
     * @param array $columns
     * @return mixed
     */
    public static function find($ids, $columns = ['*']) : mixed
    {
        $model = new self;
        $query = $model->newQuery();

        foreach ($model->getKeyName() as $key) {
            $query->where($key, '=', $ids[$key]);
        }

        return $query->first($columns);
    }
}
