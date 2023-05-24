<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegistrationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username'=> ['required','min:3','max:15','only_lowercase'],
            'email'=> ['required','email'],
            'password' => ['required','min:8','max:15','only_lowercase','confirmed']
        ];
    }
}
