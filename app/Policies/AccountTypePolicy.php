<?php

namespace App\Policies;

use App\Models\AccountType;
use App\Models\User;
use App\Models\Game;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountTypePolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountType  $accountType
     * @return mixed
     */
    public function view(User $user, AccountType $accountType)
    {
        return true;
    }

    /**
     * Determine whether the user is manager of the account type
     *
     * @param \App\Models\User $user
     * @param \App\Models\Account $accountType
     * @return bool
     */
    public function manage(User $user, AccountType $accountType): bool
    {
        return $user->can('update', $accountType->game);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Game $game)
    {
        return $user->can('update', $game);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountType  $accountType
     * @return mixed
     */
    public function update(User $user, AccountType $accountType)
    {
        return $this->manage($user, $accountType);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountType  $accountType
     * @return mixed
     */
    public function delete(User $user, AccountType $accountType)
    {
        return  false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountType  $accountType
     * @return mixed
     */
    public function restore(User $user, AccountType $accountType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccountType  $accountType
     * @return mixed
     */
    public function forceDelete(User $user, AccountType $accountType)
    {
        //
    }
}
