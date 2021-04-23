<?php

namespace App\Policies;

use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscountCode  $discountCode
     * @return mixed
     */
    public function view(User $user, DiscountCode $discountCode)
    {
        //
    }

    /**
     * Determine whether the user can manage models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return $user->hasPermissionTo('manage_discount_code');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_discount_code');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscountCode  $discountCode
     * @return mixed
     */
    public function update(User $user, DiscountCode $discountCode)
    {
        return $user->hasPermissionTo('update_discount_code')
            && ($discountCode->creator->is($user) || $this->manage($user));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscountCode  $discountCode
     * @return mixed
     */
    public function delete(User $user, DiscountCode $discountCode)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscountCode  $discountCode
     * @return mixed
     */
    public function restore(User $user, DiscountCode $discountCode)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DiscountCode  $discountCode
     * @return mixed
     */
    public function forceDelete(User $user, DiscountCode $discountCode)
    {
        //
    }
}