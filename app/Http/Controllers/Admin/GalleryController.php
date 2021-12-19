<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryCreateRequest;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class GalleryController extends ApiController
{


    public function index()
    {
        //
    }


    public function store(GalleryCreateRequest $request , Product $product)
    {
        $product->newGallery($request);
        return $this->successResponse(200 , true , 'Done!');  //true -> just upload image
    }


    public function show(Gallery $gallery)
    {
        //
    }


    public function update(Request $request, Gallery $gallery)
    {
        //
    }


    public function destroy(Gallery $gallery)
    {
        //
    }


}
