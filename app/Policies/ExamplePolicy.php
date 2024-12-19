<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Packages\Approval\Policies\ApprovalPolicy;
use Packages\Basic\Models\Administrator;

class ExamplePolicy extends ApprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return parent::viewAny($administrator) && ($administrator->can('example') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, Model $example): bool
    {
        return parent::view($administrator, $example) && ($administrator->can('example') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return parent::create($administrator) && (($administrator->can('example') && $administrator->hasRole('Editor')) || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, Model $example): bool
    {
        return parent::update($administrator, $example) && (($administrator->can('example') && $administrator->hasRole('Editor')) || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, Model $example): bool
    {
        return parent::delete($administrator, $example) && ($administrator->can('example') || $administrator->hasRole('SuperAdmin'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, Model $example): bool
    {
        return parent::restore($administrator, $example) && ($administrator->can('example') || $administrator->hasRole('SuperAdmin'));
    }
}
