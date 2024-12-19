<?php

namespace Packages\PageEditor\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Widget
 * @package Packages\Page\Models
 */
class Widget extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'uuid',
        'ranking',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'is_global' => 'bool',
    ];

    /**
     *  Reqister media collections
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 500, 500);

        $this->addMediaConversion('conversion')
            ->queued()
            ->keepOriginalImageFormat();
    }

    /**
     * 
     * 
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
