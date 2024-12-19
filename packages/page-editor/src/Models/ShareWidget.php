<?php

namespace Packages\PageEditor\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class ShareWidget
 * @package Packages\Page\Models
 */
class ShareWidget extends Model
{
    use SoftDeletes;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'enabled',
        'system',
        'component_key',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'bool',
        'system' => 'bool'
    ];

    /**
     * 
     * 
     * @return MorphMany
     */
    public function widget(): MorphMany
    {
        return $this->morphMany(Widget::class, 'model');
    }
}
