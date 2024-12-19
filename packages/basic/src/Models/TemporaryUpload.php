<?php

namespace Packages\Basic\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibraryPro\Models\TemporaryUpload  as MediaLibraryProTemporaryUpload;

class TemporaryUpload extends MediaLibraryProTemporaryUpload
{
    use HasUuids;
}
