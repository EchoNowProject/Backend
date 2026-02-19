<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'email' => ['required', 'email']
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => "El campo nombre de usuario es obligatorio",
            'email.required' => "El campo correo electrónico es obligatorio",
            'email.email' => "El campo correo electrónico tiene que ser de tipo email",
        ];
    }

    /**
     * Este metodo sirve para sacar el mensaje por consola ya que laravel si da error redirige a /welcome 
     * y por tanto daria un error 
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'user' => Auth::user(),
                'error' => $validator->errors()->first(),
            ], 422)
        );
    }
}
