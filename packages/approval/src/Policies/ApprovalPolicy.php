<?php

namespace Packages\Approval\Policies;

use Packages\Approval\Enums\ApprovalStatus;
use Packages\Basic\Models\Administrator;
use Illuminate\Database\Eloquent\Model;

class ApprovalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Administrator $administrator): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Administrator $administrator, Model $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Administrator $administrator): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Administrator $administrator, Model $model): bool
    {
        $approval = $model->approval;
        return  in_array($approval->status, [ApprovalStatus::DRAFT, ApprovalStatus::REJECTED]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Administrator $administrator, Model $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Administrator $administrator, Model $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Administrator $administrator, Model $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(Administrator $administrator, Model $model): bool
    {
        return false;
    }
}
