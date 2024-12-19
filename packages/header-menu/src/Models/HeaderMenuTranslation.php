<?php

namespace Packages\HeaderMenu\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HeaderMenuTranslation extends Model
{
    use HasUuids;

    public $timestamps = false;

    /**
     * The attributes that have translations
     * same as the $translatedAttributes in Models/Page
     *
     * @var array
     */
    protected $fillable = [
        'label',
    ];
}
