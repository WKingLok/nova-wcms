<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PhotographResource
 * @package App\Http\Resources
 */
class PhotographResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'image' =>  $this->when($this->getFirstMedia('photo'), [
                'path' => $this->resource->getFirstMediaUrl('photo', 'conversion'),
                'alt' => $this->getFirstMedia('photo')->getCustomProperty('alt'),
            ]),
        ];
    }
}
