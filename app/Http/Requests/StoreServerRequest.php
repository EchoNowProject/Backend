<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreServerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'avatar_img' => 'nullable|string',
            'type_server' => [
                'required',
                Rule::in(['public', 'private']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del servidor es obligatorio',
            'name.unique'   => 'Ya tienes un servidor con ese nombre',
            'type_server.required' => 'El tipo de servidor al que va dirigido es obligatorio',
        ];
    }

    /**
     * Este metodo sirve para sacar el mensaje por consola ya que laravel si da error redirige a /welcome 
     * y por tanto daria un error 
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
