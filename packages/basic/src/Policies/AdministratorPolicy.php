<?php

namespace Packages\Basic\Policies;

use Packages\Basic\Models\Administrator;

class AdministratorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, Administrator $administratorModel): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, Administrator $administratorModel): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, Administrator $administratorModel): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, Administrator $administratorModel): bool
    {
        return $administrator->can('administrator') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Administrator $administrator, Administrator $administratorModel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(Administrator $administrator, Administrator $administratorModel): bool
    {
        return false;
    }
}
