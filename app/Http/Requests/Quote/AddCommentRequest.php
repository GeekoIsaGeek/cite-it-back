<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'comment' => ['required','string','max:800']
        ];
    }
}
