<?php

namespace Packages\HeaderMenu\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NovaMenuCollection extends JsonResource
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
            'uuid' => $this->id,
            'label' => $this->label,
            'subMenu' => NovaMenuCollection::collection($this->child->sortBy('ranking'))
        ];
    }
}
