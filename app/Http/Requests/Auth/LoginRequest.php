<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
           'username' =>  ['required','min:3'],
           'password' => ['required']
        ];
    }
}
