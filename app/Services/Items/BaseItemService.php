<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use Batch;
use Illuminate\Support\Arr;

/**
 * Base Item Service
 *
 * @package \App\Services\Items
 */
abstract class BaseItemService
{
    /**
     * @var string
     */
    protected string $model;

    /**
     * BaseItemService constructor.
     *
     * @param string $model
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $args
     * @return object
     */
    protected function prepareCollectionParams(array $args) : object
    {
        return (object) [
            'paginated' => isset($args['paginated']) ? filter_var($args['paginated'], FILTER_VALIDATE_BOOLEAN) : false,
            'page'      => (isset($args['page']) && (int) $args['page'] > 0) ? (int) $args['page'] : 1,
            'per_page'  => (isset($args['per_page']) && (int) $args['per_page'] > 0) ? (int) $args['per_page'] : 10,
            'sort'      => (isset($args['sort']) && in_array($args['sort'], ['asc', 'desc'], true)) ? $args['sort'] : 'asc',
            'order_by'  => (isset($args['order_by']) && !empty($args['order_by'])) ? $args['order_by'] : null,
        ];
    }

    /**
     * @param array $args
     * @param array $with
     * @return mixed
     */
    protected function collection(array $args = [], array $with = []) : mixed
    {
        /*
         * Define params
         */
        $params  = $this->prepareCollectionParams($args);
        $filters = Arr::except($args, array_keys((array) $params));

        /*
         * Filter & order query
         */
        $query = (new $this->model())::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc')
            ->with($with);

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page, ['*'], 'page', $params->page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !(new $this->model())::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param array $args
     * @return mixed
     */
    protected function list(array $args = [], array $with = []) : mixed
    {
        return (new $this->model())::query()
            ->filter($args)
            ->with($with)
            ->get();
    }

    /**
     * @param array  $values
     * @param string $index
     * @return bool
     */
    protected function reorder(array $values, string $index = 'id') : bool
    {
        return !empty($values)
            ? Batch::update(new $this->model, $values, $index)
            : false;
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return mixed
     */
    protected function find(int $id, bool $throwExceptionIfFail = true) : mixed
    {
        return $throwExceptionIfFail
            ? (new $this->model())::findOrFail($id)
            : (new $this->model())::find($id);
    }

    /**
     * @param string $uuid
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    protected function findByUuid(string $uuid, bool $throwExceptionIfFail = false) : mixed
    {
        return $this->findByColumn($uuid, 'uuid', $throwExceptionIfFail);
    }

    /**
     * @param string $uuid
     * @param string $column
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    protected function findByColumn(string $uuid, string $column, bool $throwExceptionIfFail = false) : mixed
    {
        $query = (new $this->model())::where($column, $uuid);

        return $throwExceptionIfFail
            ? $query->firstOrFail()
            : $query->first();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    protected function create(array $attributes, array $loadMissing = []) : mixed
    {
        if (method_exists($this, 'prepareAttributes')) {
            $this->prepareAttributes($attributes);
        }

        $item = (new $this->model())::create($attributes);

        if ($item->exists) {
            if (method_exists($this, 'updateRelations')) {
                $this->updateRelations($item, $attributes);
            }

            if (!empty($loadMissing)) {
                $item->loadMissing($loadMissing);
            }

            return $item;
        } else {
            return null;
        }
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return mixed
     */
    protected function update(int $id, array $attributes)
    {
        $item = $this->find($id);

        if (method_exists($this, 'prepareAttributes')) {
            $this->prepareAttributes($attributes);
        }

        if (tap($item)->update($attributes)) {
            if (method_exists($this, 'updateRelations')) {
                $this->updateRelations($item, $attributes);
            }

            return $item;
        } else {
            return false;
        }
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function save(array $attributes) : mixed
    {
        if (!empty($attributes['id'])) {
            $item = $this->find($attributes['id']);

            return $this->update($item->id, $attributes);
        } else {
            return $this->create($attributes);
        }
    }

    /**
     * @param int $id
     * @return bool|null
     */
    protected function delete(int $id) : ?bool
    {
        return $this->find($id)
            ->delete();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function restore(int $id) : bool
    {
        $item = (new $this->model())::onlyTrashed()
            ->find($id);

        return $item
            ? $item->update(['deleted_at' => null])
            : false;
    }
}
