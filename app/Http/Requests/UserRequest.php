<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            return $this->createRules();
        } elseif ($this->isMethod('PUT')) {
            return $this->updateRules();
        } elseif ($this->isMethod('patch') && $this->routeIs('users.status')) {
            return $this->statusUpdateRules();
        }
    }

    /**
     * Validation rules for creating a new user.
     * 
     */
    private function createRules(): array
    {
        return [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8|max:64|regex:/^\S*$/u',
        ];
    }

    /**
     * Validation rules for updating an existing user.
     * 
     */
    private function updateRules(): array
    {
        return [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' => 'required|string|email|unique:users,email,' . $this->route('id'),
            'password' => 'nullable|min:8|max:64|regex:/^\S*$/u',
        ];
    }

    /**
     * Validation rules for updating user status (active/inactive).
     * 
     */
    private function statusUpdateRules(): array
    {
        return [
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 3 characters.',
            'first_name.max' => 'First name may not be greater than 100 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 3 characters.',
            'last_name.max' => 'Last name may not be greater than 100 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is Invalid.',
            'email.unique' => 'Email has already been taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password may not be greater than 64 characters.',
            'password.regex' => 'Spaces are not allowed for password.'
        ];
    }
}
