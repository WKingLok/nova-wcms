<?php

namespace Packages\Approval\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Packages\Approval\Models\Approval;

trait HasApproval
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $approval = true;

    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function hasApproval()
    {
        return $this->approval;
    }

    // /**
    //  * Initialize the has approval trait for an instance.
    //  *
    //  * @return void
    //  */
    // public function initializeHasApproval() {

    // }

    /**
     * Get the user's most recent image.
     */
    public function approval(): MorphOne
    {
        return $this->morphOne(Approval::class, 'approvable')->latestOfMany();
    }
}
