<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{

    public function model()
    {
        return User::class;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getWithPaginate(int $limit = 5): mixed
    {
        return $this->model->latest('id')->paginate($limit);
    }
}
