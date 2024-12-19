<?php

namespace Packages\PageEditor\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\ImageFactory;

/**
 * Class PageTranslation
 * @package Packages\PageEditor\Models
 */
class PageTranslation extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'seo_title',
        'seo_keywords',
        'seo_description'
    ];

    /**
     * The attributes that are imageable.
     *
     * @var array
     */
    public $mediaAttributes = [
        'image'
    ];

    /**
     * Disable timestamps
     *
     * @var string
     */
    public $timestamps = false;

    /**
     *  Reqister media collections
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 500, 500);

        $this
            ->addMediaCollection('image')
            ->singleFile();
    }


    public $registerMediaConversionsUsingModelInstance = true;

    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $mb = 0.29;
            $image = ImageFactory::load($media->getFullUrl());
            $width = $image->getWidth();
            $height = $image->getHeight();
            $fileSize = $media->size;

            $ratio = $height / $width;
            $area = $height * $width;

            $predictedFileSize = $fileSize;
            $pixelPrice = $predictedFileSize / $area;

            $newWidth = (int) floor(sqrt((1000000 * $mb / $pixelPrice) / $ratio));

            $this->addMediaConversion('conversion')
                ->performOnCollections($media->collection_name, 'downloads')
                ->queued()
                ->width($newWidth);
        } catch (\Throwable $th) {
        }
    }
}
