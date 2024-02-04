<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\Admin|User  $admin
     * @param  string  $ability
     * @return void|bool
     */
    public function before(Admin|User $admin, $ability)
    {
        if ($admin->is_supper_admin) {
            return true;
        }
    }

    /**
     * Determine whether the admin can view any models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $admin)
    {
        return $admin->can('members:list');
    }

    /**
     * Determine whether the admin can view the model.
     *
     * @param  \App\Models\Admin|User  $admin
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin|User $admin, User $user)
    {
        if (is_user()) {
            return $user->id == current_user()->id;
        }
        return $admin->can('members:view');
    }

    /**
     * Determine whether the admin can create models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->can('members:new');
    }

    /**
     * Determine whether the admin can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin)
    {
        return $admin->can('members:edit');
    }

    /**
     * Determine whether the admin can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin)
    {
        return $admin->can('members:delete');
    }

    /**
     * Determine whether the admin can restore the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin)
    {
        return $admin->can('members:restore');
    }

    /**
     * Determine whether the admin can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin)
    {
        return $admin->can('members:forceDelete');
    }

    /**
     * Determine whether the admin can view daily reports of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reportsDaily(Admin $admin)
    {
        return $admin->can('reports:daily-reports');
    }

    /**
     * Determine whether the admin can view monthly reports of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reportsMonthly(Admin $admin)
    {
        return $admin->can('reports:monthly-reports');
    }

    /**
     * Determine whether the admin can view yearly reports of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reportsYearly(Admin $admin)
    {
        return $admin->can('reports:yearly-reports');
    }
    /**
     * Determine whether the admin can update parq of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateParq(Admin $admin, User $user)
    {
        if (is_user()) {
            return $user->id == current_user()->id;
        }
        return $admin->can('members:enquiry');
    }

    /**
     * Determine whether the admin can view enquiry of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function enquiry(Admin $admin)
    {
        return $admin->can('members:enquiry');
    }

    /**
     * Determine whether the admin can update admin of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function admin(Admin $admin)
    {
        return $admin->can('finance:admin');
    }

    /**
     * Determine whether the admin can update membership of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function membership(Admin $admin)
    {
        return $admin->can('finance:membership');
    }

    /**
     * Determine whether the admin can update types of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function types(Admin $admin)
    {
        return $admin->can('finance:type');
    }

    /**
     * Determine whether the admin can use reconciles of the model.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reconciles(Admin $admin)
    {
        return $admin->can('finance:reconcile') || $admin->can('reconcile:rolling') || $admin->can('reconcile:yearly') || $admin->can('reconcile:end-date');
    }
}
