<?php

namespace Packages\PageEditor\Policies;

use Packages\Basic\Models\Administrator;
use Packages\PageEditor\Models\ShareWidget;

class ShareWidgetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return $administrator->can('page') || $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(Administrator $administrator, ShareWidget $shareWidget): bool
    {
        return false;
    }
}
