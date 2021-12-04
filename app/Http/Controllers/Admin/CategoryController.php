<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        //
    }


    public function store(Request $request , Category $category): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|unique:categories,title|max:255',
            'parant_id' => 'integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

        $category->newCategory($request);
        $dataResponse = $category->query()->orderBy('id','desc' )->first();
        return $this->successResponse(201 , new CategoryResource($dataResponse), 'category created successfully');

    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }


}
