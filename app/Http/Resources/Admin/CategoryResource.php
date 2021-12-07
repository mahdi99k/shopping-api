<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
//      return parent::toArray($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),  //array به صورت مجموعه ای
        ];

    }
}
