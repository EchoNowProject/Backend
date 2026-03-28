<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore(Auth::id())],
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => "El campo nombre de usuario es obligatorio",
            'username.unique' => "El campo nombre de usuario ya esta siendo utilizado por otro usuario",
            'email.required' => "El campo correo electrónico es obligatorio",
            'email.email' => "El campo correo electrónico tiene que ser de tipo email",
            'telephone_number.numeric' => 'El campo número de teléfono no es válido',
            'telephone_number.max_digits' => 'El campo número de teléfono supera el máximo de digitos permitidos',
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
