<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get client to access user_id
        $client = $this->route('client');
        $userId = $client ? $client->user_id : null;

        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'id_card_number' => 'nullable|string|max:255',
            'relation_id' => 'nullable|exists:relations,id',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'name.required' => 'Nama harus diisi.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'relation_id.exists' => 'Group/Family tidak valid.',
        ];
    }
}
