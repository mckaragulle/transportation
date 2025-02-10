<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(protected UserRepository $repository) {}

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
     * Yeni user ekler.
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
     * Useri günceller.
     */
    public function update(array $data, int $id): Model
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Useri siler.
     */
    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * User hesabına giriş yapar.
     *
     *
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function login(array $data, bool $loginByModel = false): bool
    {
        $User = $this->repository->makeModel()->where('email', $data['email'])
            ->orWhere('name', $data['email'])
            ->first();

        // Genelde user tarafında kullanılan bir özelliktir.
        // Eğer $User değişkeni varsa şifre gerekmeden login olur.
        if ($User && $loginByModel) {
            Auth::guard('web')->login($User);

            return true;
        } elseif ($User && Auth::guard('web')->attempt($data)) {
            return true;
        }

        return false;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}
