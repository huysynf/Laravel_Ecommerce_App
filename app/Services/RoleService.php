<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use App\Repositories\RoleRepository;

class RoleService
{
    protected RoleRepository $roleRepository;

    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function create($request): mixed
    {
        $dataCreate = $request->all();
        $dataCreate['guard_name'] = 'web';
        $role = $this->roleRepository->create($dataCreate);
        $role->permissions()->attach($dataCreate['permission_ids']);
        return $role;
    }

    /**
     * @param $request
     * @param $id
     * @return BaseRepository
     */
    public function update($request, $id): BaseRepository
    {
        $dataUpdate = $request->all();
        $role = $this->roleRepository->update($dataUpdate, $id);
        $role->permissions()->sync($dataUpdate['permission_ids']);
        return $role;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, array $columns = ['*']): mixed
    {
        return $this->roleRepository->findOrFail($id, $columns);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->roleRepository->delete($id);
    }

    /**
     * @return mixed
     */
    public function getWithPaginate(): mixed
    {
        return $this->roleRepository->getWithPaginate();
    }

    /**
     * @return mixed
     */
    public function getWithGroup(): mixed
    {
        return $this->roleRepository->all()->groupBy('group');
    }
}
