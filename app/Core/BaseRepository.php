<?php


namespace App\Core;


class BaseRepository
{
    protected $entity;

    public function getQuery()
    {
        return $this->entity->query();
    }

    public function getById($id)
    {
        $object = $this->entity->find($id);
        if($object)
            return $object;
        else
            return "Not found";

    }

    public function getAll( $query = null, int $perPage = 0)
    {
        $q = $this->entity;

        if ($query) {
            $q = $query;
            return $q->paginate(request()->get('limit', 20));
        }

        return $q->get();
    }

    public function store($params)
    {
        return $this->entity->create($params);
    }

    public function update(array $params, int $id)
    {
        $query = $this->getById($id);
        $query->update($params);
        return $query;
    }

    public function destroy(int $id)
    {
        $entity = $this->getById($id);
        return $entity->delete();
    }
}
