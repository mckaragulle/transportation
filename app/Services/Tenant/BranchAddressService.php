<?php

namespace App\Services\Tenant;

use App\Repositories\Tenant\BranchAddressRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BranchAddressService
{
    public function __construct(protected BranchAddressRepository $repository) {}

    public function orderBy(string $column, string $sort): Model|Builder
    {
        return $this->repository->orderBy($column, $sort);
    }

    /**
     * @param array $column
     *
     * @return Model|Builder
     */
    public function where(array $column): Model|Builder
    {
        return $this->repository->where($column);
    }

    /**
     * @return Model
     */
    public function first(): Model
    {
        return $this->repository->first();
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findById(string $id): Model
    {
        return $this->repository->first();
    }

    /**
     * @param array      $columns
     * @param null|array $relations
     * @param int        $pageSize
     *
     * @return array|Collection|LengthAwarePaginator
     */
    public function get(array $columns = ['*'], ?array $relations = null, int $pageSize = 20): array|Collection|LengthAwarePaginator
    {
        return $this->repository->get($columns, $relations, $pageSize);
    }

    /**
     * @param array      $columns
     * @param null|array $relations
     *
     * @return array|Collection
     */
    public function all(array $columns = ['*'], ?array $relations = null): array|Collection
    {
        return $this->repository->all($columns, $relations);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function insert(array $data): Model
    {
        return $this->repository->insert($data);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function insertMany(array $data): bool
    {
        return $this->repository->insertMany($data);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->repository->insert($data);
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return Model
     */
    public function update(array $data, int $id): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @param int|string $id
     *
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}
