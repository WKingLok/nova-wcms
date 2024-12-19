<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Packages\Approval\Policies\ApprovalPolicy;
use Packages\Basic\Models\Administrator;

class PhotographPolicy extends ApprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return parent::viewAny($administrator) && ($administrator->can('photograph') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, Model $photograph): bool
    {
        return parent::view($administrator, $photograph) && ($administrator->can('photograph') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return parent::create($administrator) && (($administrator->can('photograph') && $administrator->hasRole('Editor')) || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, Model $photograph): bool
    {
        return parent::update($administrator, $photograph) && (($administrator->can('photograph') && $administrator->hasRole('Editor')) || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, Model $photograph): bool
    {
        return parent::delete($administrator, $photograph) && ($administrator->can('photograph') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, Model $photograph): bool
    {
        return parent::restore($administrator, $photograph) && ($administrator->can('photograph') || $administrator->hasRole('SuperAdmin'));
    }
}
