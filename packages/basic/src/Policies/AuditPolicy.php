<?php

namespace Packages\Basic\Policies;

use Packages\Basic\Models\Administrator;
use OwenIt\Auditing\Models\Audit;

/**
 * ! delete all $administrator->hasRole('SuperAdmin') if the project is not supporting approval flow feature
 */
class AuditPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, Audit $audit): bool
    {
        return $administrator->hasRole('SuperAdmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, Audit $audit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, Audit $audit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, Audit $audit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Administrator $administrator, Audit $audit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(Administrator $administrator, Audit $audit): bool
    {
        return false;
    }
}
