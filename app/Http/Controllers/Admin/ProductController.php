<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{


    public function index()
    {
        $product = Product::query()->paginate(10);
        return $this->successResponse(201, [
            'products' => ProductResource::collection($product),
            'links' => ProductResource::collection($product)->response()->getData()->links,                          // paginator orginal
            'meta' => ProductResource::collection($product)->response()->getData()->meta,                          // paginator مشخصات فعال و غیرفعال
        ], 'get products');
    }


    public function store(Request $request, Product $product)
    {

        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',  //exists آیدی جدول دسته بندی ها وجود داشته باشد و یکی باش با آیدی محصولات
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:150',
            'slug' => 'required|unique:products,slug|alpha_dash',  //alpha_dash بین کلمات خالی نباشه
            'description' => 'required|string|max:5000',
            'image' => 'required|image|mimes:png,jpg,jpeg,svg|min:10|max:2048',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }


        $product->newProduct($request);
        $responseData = $product->query()->orderBy('id', 'desc')->first();
        return $this->successResponse(200, new ProductResource($responseData), 'product created successfully');
    }


    public function show(Product $product)
    {
        return $this->successResponse(200, new ProductResource($product), 'GET SHOW' . ' ' . $product->name);
    }


    public function update(Request $request, Product $product)
    {
//      $product = Product::all();
//      'slug' => "required|unique:products,slug,$product->slug|alpha_dash", //unique یونیک باش محصول در ستون اسلاگ و حالت سوم یعنی به جز اسلتاگ  بررسی کن

        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',  //exists آیدی جدول دسته بندی ها وجود داشته باشد و یکی باش با آیدی محصولات
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:150',
            'slug' => 'required|alpha_dash',  //alpha_dash بین کلمات خالی نباشه
            'description' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|min:10|max:2048',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

        $slugUnique = Product::query()->where('slug', '=', $request->slug)->where('id', '!=', $product->id)->exists();
        if ($slugUnique) {
            return $this->errorResponse(422, 'The slug has already been taken!');
        }

        $product->updateProduct($request);
        return $this->successResponse(200, new ProductResource($product), $product->name . ' ' . 'updated successfully');

    }


    public function destroy(Product $product)
    {
        $product->deleteProduct($product);
        return $this->successResponse(422, $product->name . ' ' . 'deleted successfully');
    }


}
