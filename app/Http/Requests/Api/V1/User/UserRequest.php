<?php

namespace App\Http\Requests\Api\V1\User;

use App\Rules\Phone;
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
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', new Phone, Rule::unique('users', 'phone')->ignore(auth('api')->id())],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore(auth('api')->id())],
        ];
    }
}
