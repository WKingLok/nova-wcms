<?php

namespace Packages\Basic\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaLibraryMedia;

class Media extends MediaLibraryMedia
{
    use HasUuids;
}
