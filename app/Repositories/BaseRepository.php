<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

abstract class BaseRepository implements RepositoryInterface
{
    protected Application $app;

    protected Model $model;

    /**
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract protected function model(): string|Model;

    /**
     * @throws RepositoryException|BindingResolutionException
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function orderBy($column, $sort): Builder
    {
        return $this->model->orderBy($column, $sort);
    }

    public function where(array $column): Builder
    {
        return $this->model->where($column);
    }

    public function first(): Model
    {
        return $this->model->first();
    }

    public function findById(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return array|Collection
     */
    public function get(array $columns = ['*'], ?array $relations = null, int $pageSize = 20): array|Collection|LengthAwarePaginator
    {
        if (is_array($relations)) {
            $this->model->with($relations);
        }

        return $this->model->paginate($pageSize, $columns);
    }

    public function all(array $columns = ['*'], ?array $relations = null): array|Collection
    {
        if (is_array($relations)) {
            $this->model->with($relations);
        }

        return $this->model->get($columns);
    }

    public function insert(array $data): Model
    {
        return $this->model->create($data);
    }

    public function insertMany(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->model->findOrFail($id);
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return call_user_func_array([new static(), $name], $arguments);
    }
}
