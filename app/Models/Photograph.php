<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Packages\Approval\Traits\HasApproval;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Photograph.
 *
 * @package namespace App\Models;
 */
class Photograph extends Model implements Auditable, HasMedia
{
    use HasUuids;
    use HasApproval;
    use SoftDeletes;
    use InteractsWithMedia;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The model's attributes.
     *
     * @var void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile();
    }

    /**
     * The model's attributes.
     *
     * @var void
     */

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 500, 500)
            ->nonQueued();
    }
}
