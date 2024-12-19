<?php

namespace Packages\PageEditor\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keyword' => $this->seo_keywords,
            ]
        ];
    }
}
