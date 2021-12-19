<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GalleryCreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => '',
            'path.*' => 'nullable|image|mimes:jpg,jpeg,png,svg|min:5|max:2048',  //path.* -> is array
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'validation errors',
            'attribute' => $validator->errors(), //show error
        ]));
    }


}
