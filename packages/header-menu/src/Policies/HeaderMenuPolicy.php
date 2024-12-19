<?php

namespace Packages\HeaderMenu\Policies;

use Packages\Basic\Models\Administrator;
use Packages\HeaderMenu\Models\HeaderMenu;

class HeaderMenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(Administrator $administrator, HeaderMenu $headerMenu): bool
    {
        return false;
    }

    /**
     * Determine whether the user can reorder the model.
     */
    public function reorder(Administrator $administrator): bool
    {
        return $administrator->can('menu') || $administrator->hasRole('SuperAdmin');
    }
}
