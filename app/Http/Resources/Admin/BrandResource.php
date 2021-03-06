<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{

    public function toArray($request)
    {
//      return parent::toArray($request);

        return [
          'id' => $this->id,
          'title' => $this->title,
          'products' => ProductResource::collection($this->whenLoaded('products')),
        ];

    }

}
