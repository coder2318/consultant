<?php


namespace App\Core;


use Illuminate\Database\Eloquent\Builder;

class BaseService
{
    protected $repo;
    protected $relation;
    protected $filter_fields;

    public function get($request)
    {
        $query = $this->repo->getQuery();
        $query = $this->relation($query, $this->relation);
        $query = $this->filter($query, $this->filter_fields, $request);
        $query = $this->sort($query);
        $query = $this->repo->getAll($query);
        return $query;

    }

    public function relation(Builder $query, $relation = null)
    {
        if ($relation) {
            $query->with($relation);
        }
        return $query;
    }

    public function filter(Builder $query, $filter_fields, $request)
    {
        foreach ($filter_fields as $key => $item) {
            if (array_key_exists($key, $request)) {
                if ($item['type'] == 'string')
                    $query->where($key, 'like', '%' . $request[$key] . '%');
                if ($item['type'] == 'number')
                    $query->where($key, $request[$key]);

            }
        }
        return $query;
    }

    public function sort($query): Builder
    {
        $key = request()->get('sort_key', 'id');
        $order = request()->get('sort_order', 'desc');
        $query->orderBy($key, $order);

        return $query;
    }

    public function create($params)
    {
        return $this->repo->store($params);
    }

    public function show($id)
    {
        return $this->repo->getById($id);
    }

    public function update($params, $id)
    {
        return $this->repo->update($params, $id);
    }

    public function delete(int $id)
    {
        return $this->repo->destroy($id);
    }
}
