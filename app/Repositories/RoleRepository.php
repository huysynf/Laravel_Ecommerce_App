<?php
namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{

    public function model()
    {
        return Role::class;
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function getWithPaginate($limit = 5): mixed
    {
        return $this->model->latest('id')->paginate($limit);
    }
}
