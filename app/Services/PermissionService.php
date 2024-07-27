<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PermissionService
{
    public function __construct(protected readonly PermissionRepository $repository)
    {
    }

    /**
     * Yeni öğretim görevlisi ekler.
     */
    public function create(array $data): Model
    {
        return $this->repository->insert($data);
    }

    public function findById(int $id): Model
    {
        return $this->repository->findById($id);
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
     * Öğretim görevlisini günceller.
     */
    public function update(array $data, int $id): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Öğretim görevlisini siler.
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
