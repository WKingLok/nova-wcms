<?php

namespace Packages\PageEditor\Models;

use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 * @package Packages\Page\Models
 */
class Page extends Model implements TranslatableContract, HasMedia
{
    use SoftDeletes;
    use Translatable;
    use InteractsWithMedia;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'path',
        'enabled'
    ];


    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'seo_title',
        'seo_keywords',
        'seo_description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enable' => 'bool',
    ];

    // public function toSitemapTag(): Url | string | array
    // {
    //     return [
    //         Url::create(env('APP_FRONTEND_URL') . $this->path)
    //             ->setLastModificationDate(Carbon::create($this->updated_at))
    //             ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
    //             ->setPriority(0.1),
    //         Url::create(env('APP_FRONTEND_URL') . '/tc' . $this->path)
    //             ->setLastModificationDate(Carbon::create($this->updated_at))
    //             ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
    //             ->setPriority(0.1),
    //         Url::create(env('APP_FRONTEND_URL') . '/sc' . $this->path)
    //             ->setLastModificationDate(Carbon::create($this->updated_at))
    //             ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
    //             ->setPriority(0.1)
    //     ];
    // }

    /**
     * 
     * 
     * @return MorphMany
     */
    public function widgets(): MorphMany
    {
        return $this->morphMany(Widget::class, 'model');
    }
}
