<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserPostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|max:100',
            'phone' => 'required|regex:/^[\+]{0,1}380([0-9]{9})$/',
            'position_id' => 'required|integer|exists:positions,id',
            // 'photo' => 'required|file|image|mimes:jpeg,jpg|max:5120|dimensions:min_width=70,min_height=70',  // should use this later 
            'photo' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator) 
    {
        // $data = $this->all();

        $existingUser = User::where('email', $this->email)
            ->orWhere('phone', $this->phone)
            ->first();
        if ($existingUser) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => "User with this phone or email already exist.",
            ], 409));
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => "Validation failed",
            'fails' => [
                'name' => "The name must be at least 2 characters.",
                'email' => "The email must be a valid email address.",
                'phone' => "The phone field is required.",
                'position_id' => "The position id must be an integer.",
                'photo' => "The photo may not be greater than 5 Mbytes. Image is invalid.",
            ],
        ], 422));
    }
}
