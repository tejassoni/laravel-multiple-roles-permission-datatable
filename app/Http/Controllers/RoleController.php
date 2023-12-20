<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Spatie\Permission\Models\Role; // KEY : MULTIPERMISSION
use Spatie\Permission\Models\Permission; // KEY : MULTIPERMISSION

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //KEY : MULTIPERMISSION
        $this->middleware('permission:role-list|role-create|role-edit|role-show|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        $this->middleware('permission:role-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('updated_at', 'desc')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $role = Role::firstOrCreate(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            if ($role) {
                \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success inserting data : " . json_encode([request()->all()]));
                return redirect()->route('roles.index')
                    ->with('success', 'Role created successfully');
            }
            throw new \Exception('fails not created..!', 403);
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query inserting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error inserting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get()->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $role->syncPermissions($request->permission);
            $role = $role->updateOrFail(['name' => $request->input('name')]);
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success updating data : " . json_encode([request()->all(), $role]));
            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully');
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query updating data : " . $e->getMessage());
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success deleting data : " . json_encode([request()->all(), $role]));
            return redirect()->route('roles.index')
                ->withSuccess('Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query deleting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error deleting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }


}