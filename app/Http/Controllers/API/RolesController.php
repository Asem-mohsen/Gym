<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\Roles\AddRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Models\Role;

class RolesController extends Controller
{
    use ApiResponse;

    // works
    public function index()
    {
        $roles = Role::all();

        return $this->data(compact('roles') , 'role data retrived successfully' , 200);
    }

    // works
    public function store(AddRequest $request)
    {
        $data = $request->except('_method' , 'token');

        $newRole = Role::create($data);

        return $this->data(compact('newRole') , 'Role created successfully' , 200);
    }

    // works
    public function edit(Role $role)
    {
        return $this->data(compact('role') , 'Role retrieved successfully' , 200);
    }

    // works
    public function update(UpdateRequest $request , Role $role)
    {
        $data = $request->except('_method' , 'token');

        Role::where('id' ,  $role->id)->update($data);

        return $this->success('Role updated successfully');
    }

    // works
    public function destroy(Request $request , Role $role)
    {
        Role::where('id' ,  $role->id)->delete();

        return $this->success('Role deleted successfully');
    }
}
