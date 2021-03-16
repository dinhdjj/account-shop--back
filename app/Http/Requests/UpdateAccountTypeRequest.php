<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'rolesCanUsedAccountType' => 'nullable|array',
            'rolesCanUsedAccountType.*' => 'array',
            'rolesCanUsedAccountType.*.id' => 'required|integer',
            'rolesCanUsedAccountType.*.statusCode' => 'required|integer',
        ];
    }
}
