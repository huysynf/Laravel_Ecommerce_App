<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected UserService $userService;
    protected RoleService $roleService;

    /**
     * @param UserService $userService
     * @param RoleService $roleService
     */
    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $users = $this->userService->getWithPaginate();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->roleService->getWithGroup();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $this->userService->create($request);
        return to_route('users.index')->with(['message' => 'create success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userService->findOrFail($id)->load('roles');
        $roles = $this->roleService->getWithGroup();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int|string $id
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, int|string $id): \Illuminate\Http\RedirectResponse
    {
        $this->userService->update($request, $id);
        return to_route('users.index')->with(['message' => 'Update success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userService->delete($id);

        return redirect()->route('users.index')->with(['message' => 'Delete success']);
    }
}
