<?php

namespace App\Services;

use App\Repositories\CityRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CityService
{
    public function __construct(protected readonly CityRepository $repository) {}

    /**
     * 
     * 
     * @param mixed $column
     * @param mixed $sort
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function orderBy($column, $sort): Builder|Model
    {
        return $this->repository->orderBy($column, $sort = 'asc');
    }

    /**
     * @return array|Collection
     */
    public function get(array $columns = ['*'], ?array $relations = null, int $pageSize = 20): array|Collection|LengthAwarePaginator
    {
        return $this->repository->get($columns, $relations, $pageSize);
    }

    public function all(array $columns = ['*'], ?array $relations = null): array|Collection
    {
        return $this->repository->all($columns, $relations);
    }

    /**
     * Yeni admin ekler.
     */
    public function create(array $data): Model
    {
        return $this->repository->insert($data);
    }

    public function findById(string $id): Model
    {
        return $this->repository->findById($id);
    }

    /**
     * Admini günceller.
     */
    public function update(array $data, int $id): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Admini siler.
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}
