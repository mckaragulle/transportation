<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * @return Model
     */
    public function orderBy(string $column, string $sort): Builder;

    /**
     * @return Model
     */
    public function where(array $column): Builder;

    public function first(): Model;

    public function findById(int $id): Model;

    /**
     * @return array|Collection
     */
    public function get(array $columns = ['*'], ?array $relations = null, int $pageSize = 20): array|Collection|LengthAwarePaginator;

    public function all(array $columns = ['*'], ?array $relations = null): array|Collection;

    public function insert(array $data): Model;

    public function insertMany(array $data): bool;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;
}
