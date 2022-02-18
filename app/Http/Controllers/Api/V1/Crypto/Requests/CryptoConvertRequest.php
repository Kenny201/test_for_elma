<?php

namespace App\Http\Controllers\Api\V1\Crypto\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CryptoConvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency_from' => 'required',
            'currency_to' => 'required',
            'source_id' => 'required|integer',
            'value' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'currency_from.required' => 'Заполните название криптовалюты',
            'currency_to.required' => 'Заполните название валюты',
            'value.required' => 'Укажите количество',
            'value.integer' => 'Ожидается число',
            'source_id.required' => 'Укажите сервис через который будет происходить конвертация валюты',
            'source_id.integer' => 'Ожидается число'
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => false
        ], 422,
            [],
            JSON_UNESCAPED_UNICODE)
        );
    }
}
