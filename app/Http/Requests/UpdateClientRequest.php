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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
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
            'name.required' => 'Nama harus diisi.',
            'relation_id.exists' => 'Group/Family tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'relation_id.exists' => 'Group/Family tidak valid.',
        ];
    }
}
