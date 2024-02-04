<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Instructor;
use Coderstm\Models\Group;
use Coderstm\Models\Module;
use Illuminate\Http\Request;
use Coderstm\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Coderstm\Http\Controllers\Controller;
use App\Notifications\NewStaffNotification;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Admin::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Admin $admin)
    {
        $admin = $admin->with('lastLogin', 'groups');

        if ($request->has('filter') && !empty($request->filter)) {
            $admin->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->filter}%");
            $admin->orWhere('email', 'like', "%{$request->filter}%");
        }

        if ($request->has('group') && !empty($request->group)) {
            $admin->whereHas('groups', function ($query) use ($request) {
                $query->where('id', $request->group);
            });
        }

        if ($request->boolean('active')) {
            $admin->onlyActive();
        }

        if ($request->boolean('hideCurrent')) {
            $admin->excludeCurrent();
        }

        if ($request->boolean('deleted')) {
            $admin->onlyTrashed();
        }

        $admin = $admin->sortBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($admin);
    }

    /**
     * Display a options listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, Admin $admin)
    {
        $request->merge([
            'option' => true
        ]);
        return $this->index($request, $admin);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Admin $admin)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $password = $request->filled('password') ? $request->password : fake()->regexify('/^IN@\d{3}[A-Z]{4}$/');

        $request->merge([
            'password' => bcrypt($password),
        ]);

        $admin = $admin->create($request->input());

        $admin->syncGroups(collect($request->groups));

        $admin->syncPermissions(collect($request->permissions));

        $admin->notify(new NewStaffNotification($admin, $password));

        return response()->json([
            'data' => $admin->load('groups', 'permissions'),
            'message' => trans('coderstm::messages.staff.store'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $admin = $admin->load([
            'permissions',
            'groups',
            'lastLogin',
        ]);
        return response()->json($this->toArray($admin), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        // Set rules
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|unique:admins,email,' . $admin->id,
            'password' => 'min:6|confirmed',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        if ($request->filled('password')) {
            $request->merge([
                'password' => bcrypt($request->password),
            ]);
        }

        if ($admin->id == current_user()->id) {
            $admin->update($request->except(['is_active', 'is_supper_admin']));
        } else {
            $admin->update($request->input());
        }

        $admin->syncGroups(collect($request->groups));

        $admin->syncPermissions(collect($request->permissions));

        return response()->json([
            'data' => $this->toArray($admin->load('groups', 'permissions')),
            'message' => trans('coderstm::messages.staff.updated'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json([
            'message' => trans_choice('coderstm::messages.staff.destroy', 1),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy_selected(Request $request, Admin $admin)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $admin->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_choice('coderstm::messages.staff.destroy', 2),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Admin::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_choice('coderstm::messages.staff.restored', 1),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function restore_selected(Request $request, Admin $admin)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $admin->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_choice('coderstm::messages.staff.restored', 2),
        ], 200);
    }

    /**
     * Display a listing of the permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function modules(Request $request)
    {
        $groups = Group::with('permissions')->get();
        $permissions = Permission::all();
        $permissionByModule = $permissions->groupBy('module_id');

        $modules = Module::select('id', 'name', 'icon', 'url', 'show_menu', 'sort_order')
            ->find($permissionByModule->keys())
            ->map(function ($item) {
                $item->label = trans('coderstm::modules.' . $item->name);
                return $item;
            });

        foreach ($modules as &$module) {
            $module['permissions'] = $permissionByModule->get($module->id);
        }

        return response()->json([
            'modules' => $modules,
            'groups' => $groups,
        ], 200);
    }

    /**
     * Send reset password request to specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function resetPasswordRequest(Request $request, Admin $admin)
    {
        $status = Password::sendResetLink([
            'email' => $admin->email,
        ]);

        return response()->json([
            'status' => $status,
            'message' => trans('coderstm::messages.staff.password'),
        ], 200);
    }

    /**
     * Change admin of specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function changeAdmin(Request $request, Admin $admin)
    {
        if ($admin->id == current_user()->id) {
            return response()->json([
                'message' => trans('coderstm::messages.staff.admin_error'),
            ], 403);
        }

        $admin->update([
            'is_supper_admin' => !$admin->is_supper_admin
        ]);

        $type = $admin->is_supper_admin ? 'marked' : 'unmarked';

        return response()->json([
            'message' => trans('coderstm::messages.staff.admin_success', ['type' => trans('coderstm::messages.attributes.' . $type)]),
        ], 200);
    }

    /**
     * Change active of specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function changeActive(Request $request, Admin $admin)
    {
        if ($admin->id == current_user()->id) {
            return response()->json([
                'message' => trans('coderstm::messages.staff.reply'),
            ], 403);
        }

        $admin->update([
            'is_active' => !$admin->is_active
        ]);

        $type = !$admin->is_active ? 'active' : 'deactive';

        return response()->json([
            'message' => trans('coderstm::messages.staff.status', ['type' => trans('coderstm::messages.attributes.' . $type)]),
        ], 200);
    }

    public function changeInstructor(Request $request, Admin $admin)
    {
        $admin->update([
            'is_instructor' => !$admin->is_instructor
        ]);

        return response()->json([
            'message' => trans('messages.staff_instructor', ['type' => $admin->is_instructor ? 'marked' : 'unmarked']),
        ], 200);
    }

    private function toArray(Admin $admin)
    {
        $data = $admin->toArray();

        $data['permissions'] = $admin->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'access' => $permission->pivot->access,
            ];
        });

        $data['groupPermissions'] = $admin->getPermissionsViaGroups()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'access' => $permission->pivot->access,
            ];
        });

        return $data;
    }
}
