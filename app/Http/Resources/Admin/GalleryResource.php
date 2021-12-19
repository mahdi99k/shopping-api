<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    public function toArray($request)
    {
//      return parent::toArray($request);
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
//          'path' =>  url(env('IMAGE_UPLOADED_FOR_PRODUCTS'). $this->path),
            'path' =>  $this->path,
            'mime' => $this->mime,
        ];
    }
}
