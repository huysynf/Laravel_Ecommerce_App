<?php

namespace Tests\Unit\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    protected RoleRepository $roleRepository;

    public function setUp():void
    {
        parent::setUp();
        $this->roleRepository = app(RoleRepository::class);
    }

    /** @test */
    public function test_it_can_create_role_if_data_is_validate()
    {
        $dataCreate =  Role::factory()->make()->toArray();
        $countRoleBefore = Role::count();
        $role = $this->roleRepository->create($dataCreate);
        $countRoleAfter = Role::count();

        $this->assertEquals($countRoleBefore + 1, $countRoleAfter);
        $this->assertDatabaseHas('roles', $dataCreate);
    }
}
