<?php

namespace App\Services;

use App\Repositories\CityRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CityService
{
    public function __construct(protected readonly CityRepository $repository) {}

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

    /**
     * Admin hesabına giriş yapar.
     *
     *
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function login(array $data, bool $loginByModel = false): bool
    {
        $Admin = $this->repository->makeModel()->where('email', $data['email'])
            ->orWhere('name', $data['email'])
            ->first();

        // Genelde admin tarafında kullanılan bir özelliktir.
        // Eğer $Admin değişkeni varsa şifre gerekmeden login olur.
        if ($Admin && $loginByModel) {
            Auth::guard('admin')->login($Admin);

            return true;
        } elseif ($Admin && Auth::guard('admin')->attempt($data)) {
            return true;
        }

        return false;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}
