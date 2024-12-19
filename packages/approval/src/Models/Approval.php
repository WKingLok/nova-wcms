<?php

namespace Packages\Approval\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use Packages\Approval\Enums\ApprovalAction;
use Packages\Approval\Enums\ApprovalStatus;

/**
 * Class Approval.
 *
 * @package namespace Packages\Approval\Models;
 */
class Approval extends Model implements Auditable
{
    use HasUuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'clone_from',
        'approvable_id',
        'approvable_type',
        'status',
        'action',
        'version',
        'history'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'history' => 'array',
        'status' => ApprovalStatus::class,
        'action' => ApprovalAction::class,
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        //
    ];

    /**
     * Get the model that the image belongs to.
     */
    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'approvable_type', 'approvable_id');
    }
}
