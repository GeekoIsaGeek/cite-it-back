<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, $this->ajaxOrJson()
            ? response()->json([
                'errors' => $validator->errors(),
            ], 422)
            : parent::failedValidation($validator)
        );
    }
    protected function ajaxOrJson():bool
    {
        return $this->ajax() || $this->wantsJson();
    }
}
