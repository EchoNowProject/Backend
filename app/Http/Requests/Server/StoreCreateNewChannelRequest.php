<?php

namespace App\Http\Requests\Server;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCreateNewChannelRequest extends FormRequest
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
            'server_id' => ['required', 'integer', 'exists:servers,id'],
            'channel_text_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'server_id.required' => 'El server_id es obligatorio.',
            'server_id.integer' => 'El server_id debe ser un número entero.',
            'server_id.exists' => 'El servidor seleccionado no existe.',

            'channel_text_name.required' => 'El nombre del canal es obligatorio.',
            'channel_text_name.string' => 'El nombre del canal debe ser texto.',
            'channel_text_name.max' => 'El nombre del canal no puede superar los 255 caracteres.',
        ];
    }
}
