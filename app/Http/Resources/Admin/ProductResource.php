<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
//      return parent::toArray($request);     // custom return view api

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'image' => url(env('IMAGE_UPLOADED_FOR_PRODUCTS'). $this->image),  //link
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'galleries' => GalleryResource::collection($this->galleries), // collection مجموعه ای | whenLoaded استفاده نکردیم چون قرار نیست لود بشه چیزی
        ];

    }
}
