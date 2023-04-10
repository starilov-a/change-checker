<?php


namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ValidateRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        foreach ($validator->failed() as $field => $errs) {
            $errorString[$field] = $field;
        }

        abort(response()->json([
            'code' => 422,
            'message' => 'Неверные входные значения - '. implode(', ',$errorString)], 422)
        );
    }
}
