<?php

namespace App\Services;

use App\Repositories\DealerLogoRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DealerLogoService
{
    public function __construct(protected readonly DealerLogoRepository $repository) {}

    public function orderBy($column, $sort): Model|Builder
    {
        return $this->repository->orderBy($column, $sort);
    }

    public function where(array $column): Model|Builder
    {
        return $this->repository->where($column);
    }

    public function first(): Model
    {
        return $this->repository->first();
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
     * Yeni bayi ekler.
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
     * Bayiyi günceller.
     */
    public function update(array $data, int $id): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Bayiyi siler.
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