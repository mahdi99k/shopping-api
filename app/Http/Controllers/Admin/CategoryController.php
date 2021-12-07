<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $category = Category::paginate(10);

        return $this->successResponse(200, [
            'categories' => CategoryResource::collection($category),
            'links' => CategoryResource::collection($category)->response()->getData()->links,                          // paginator orginal
            'meta' => CategoryResource::collection($category)->response()->getData()->meta,                          // paginator مشخصات فعال و غیرفعال
//          'links' => CategoryResource::collection($category)->response()->getDate()->links,
//          'meta' => CategoryResource::collection($category)->response()->getDate()->meta,
        ], 'get categories');
    }


    public function store(Request $request, Category $category): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|unique:categories,title|max:255',
            'parant_id' => 'integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

        $category->newCategory($request);
        $dataResponse = $category->query()->orderBy('id', 'desc')->first();
        return $this->successResponse(201, new CategoryResource($dataResponse), 'category created successfully');

    }


    public function show(Category $category)
    {
        return $this->successResponse(200, new CategoryResource($category), 'GET' . '_' . $category->title);
    }


    public function update(Request $request, Category $category)
    {
        //اگر عنوان برابر عنوان درون دیتابیس بود و اگر ایدی دو بار تکرار شده بود و وجود داشت یعنی یونیک نبوده
        $categoryUnique = Category::query()->where('title', $request->title)->where('id', '!=', $category->id)->exists();
        if ($categoryUnique) {
            return $this->errorResponse(422, 'The title has already been taken');
        }

        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422 , $validate->messages());
        }

        $category->updateCategory($request);
        return $this->successResponse(200 , new CategoryResource($category) , 'category updated successfully');

    }


    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        $category->deleteCategory($category);
        return $this->successResponse(200 , new CategoryResource($category) , 'category deleted successfully');
    }


    //----------------------------------------- me

    public function parent(Category $category): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(200, new CategoryResource($category->load('parent'))  , 'GET parents');
    }


    public function children(Category $category)
    {
        return $this->successResponse(200 , new CategoryResource($category->load('children')) , 'GET childrens');
    }


}
