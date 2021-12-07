<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{


    public function index(): \Illuminate\Http\JsonResponse
    {
        $brand = Brand::all();
        return $this->successResponse(200, BrandResource::collection($brand), 'brand get ok');
    }


    public function store(Request $request, Brand $brand)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:250|unique:brands,title',
            'image' => 'required|image|mimes:jpg,jpeg,png,svg|max:5000',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

        $brand->newBrand($request);
        $dataResponse = $brand->query()->orderBy('id', 'desc')->first();
        return $this->successResponse(201, new BrandResource($dataResponse), 'brand created successfully');

    }


    public function show(Brand $brand)
    {
        return $this->successResponse(200 , new BrandResource($brand) , 'GET' . '_' . $brand->title);
    }


    public function update(Request $request, Brand $brand)
    {
        //اگر عنوان برابر عنوان درون دیتابیس بود و اگر ایدی دو بار تکرار شده بود و وجود داشت یعنی یونیک نبوده
        $brandUnique = Brand::query()->where('title', '=', $request->title)->where('id', '!=', $brand->id)->exists();
        if ($brandUnique) {
            return $this->errorResponse(422, "The title has already been taken!");
        }

        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'image' => 'image|mimes:jpg,jpeg,png,svg|max:5000',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }


        $brand->updateBrand($request);
        return $this->successResponse(200, new BrandResource($brand), 'brand updated successfuly');
    }


    public function destroy(Brand $brand)
    {
        $brand->deleteBrand($brand);
        return $this->successResponse(200, $brand->title . ' ' . 'deleted successfully');
    }


}
