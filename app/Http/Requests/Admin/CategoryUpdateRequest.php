<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'parent_id' => 'nullable|integer',
        ];
    }


    public function failedValidation(Validator $validator)   //Contracts/Validation
    {
        throw new  HttpResponseException(response()->json([
            'status' => false,
            'message' => 'validations errors',
            'attribute' => $validator->errors(),
        ]));
    }

}
