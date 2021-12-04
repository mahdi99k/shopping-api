<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|unique:categories,title|max:255',
            'parent_id' => 'integer',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

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
