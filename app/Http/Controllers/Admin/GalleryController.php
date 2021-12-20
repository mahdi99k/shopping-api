<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryCreateRequest;
use App\Http\Resources\Admin\GalleryResource;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class GalleryController extends ApiController
{


    public function index(Product $product)
    {
        return $this->successResponse(200, GalleryResource::collection($product->galleries), 'images for product');
    }


    public function store(GalleryCreateRequest $request, Product $product)
    {
        $product->newGallery($request);
        return $this->successResponse(200, true, 'uploaded successfully');  //true -> just upload image
    }


    public function show(Gallery $gallery)
    {
        //
    }


    public function update(Request $request, Gallery $gallery)
    {
        //
    }


    public function destroy(Product $product, Gallery $gallery)
    {
        $product->deleteGallery($gallery);
        return $this->successResponse(200 , true , 'image deleted successfully');
    }


}
