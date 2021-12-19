<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryCreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => 'required|string|unique:categories,title|max:255',
            'parent_id' => 'integer|exists:categories,id',
        ];
    }


    public function failedValidation(Validator $validator)  //Contracts/Validation | showMessage
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'validation errors',
            'attribute' => $validator->errors(),  //show errors
        ]));
    }


    /* public function messages()
    {
        return [
            'title.required' => 'عنوان دسته بندی نباید خالی باشد',
        ];
    } */


}
