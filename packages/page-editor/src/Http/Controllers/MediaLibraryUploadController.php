<?php

namespace Packages\PageEditor\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibraryPro\Models\TemporaryUpload;
use Spatie\MediaLibraryPro\Http\Controllers\MediaLibraryUploadController as ControllersMediaLibraryUploadController;

class MediaLibraryUploadController extends ControllersMediaLibraryUploadController
{
    protected function responseFields(Media $media, TemporaryUpload $temporaryUpload): array
    {
        $fields = parent::responseFields($media, $temporaryUpload);

        return [
            ...$fields,
            'original_url' => $media->getUrl(),
        ];
    }
}
