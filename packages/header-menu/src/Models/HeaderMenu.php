<?php

namespace Packages\HeaderMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Packages\HeaderMenu\Enums\HeaderMenuType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Packages\PageEditor\Models\Page;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Class HeaderMenu.
 *
 * @package namespace App\Models;
 */
class HeaderMenu extends Model implements TranslatableContract, Auditable
{
    use Translatable;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'type',
        'enabled',
        'parent_id',
        'ranking',
        'url'
    ];

    /**
     * The attributes that have translations
     *
     * @var array
     */
    public $translatedAttributes = [
        'label',
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
        'enable' => 'bool',
        'type' => HeaderMenuType::class
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ranking'   => 0,
    ];

    public function child(): HasMany
    {
        return $this->hasMany(HeaderMenu::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(HeaderMenu::class, 'parent_id');
    }

    /**
     * 
     * 
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    protected function path(): Attribute
    {
        return Attribute::get(fn() => $this->type == HeaderMenuType::EXTERNAL ?  $this->url : data_get($this, 'page.path'));
    }
}
