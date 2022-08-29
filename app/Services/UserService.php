<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Traits\HandleImageTrait;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use HandleImageTrait;
    protected UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function create($request): mixed
    {
        $dataCreate = $request->all();
        $dataCreate['password'] = Hash::make($request->password);
        $dataCreate['image'] = $this->saveImage($request);
        $user =  $this->userRepository->create($dataCreate);
        $user->syncImage($dataCreate['image']);
        $user->assignRoles($dataCreate['role_ids'] ?? []);

        return $user;
    }

    /**
     * @param $request
     * @param $id
     * @return BaseRepository
     */
    public function update($request, $id): BaseRepository
    {
        $dataUpdate = $request->except('password');
        $user = $this->findOrFail($id);
        if ($request->password) {
            $dataCreate['password'] = Hash::make($request->password);
        }
        $dataUpdate['image'] = $this->updateImage($request, $user?->images?->first()?->url);
        $user->update($dataUpdate);
        $user->syncImage($dataUpdate['image']);
        $user->assignRoles($dataUpdate['role_ids'] ?? []);
        return $user;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, array $columns = ['*']): mixed
    {
        return $this->userRepository->findOrFail($id, $columns);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        $user = $this->findOrFail($id);
        $user->destroyImage($user?->images?->first()?->url);
        $this->deleteImage($user?->images?->first()?->url);
        $user->delete();
        return $user;
    }

    /**
     * @return mixed
     */
    public function getWithPaginate(): mixed
    {
        return $this->userRepository->getWithPaginate();
    }
}
