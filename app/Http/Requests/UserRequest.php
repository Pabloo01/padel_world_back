<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Translator\Translator;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['nullable','numeric', 'digits:9'],
            'address' => ['required','string', 'max:255']

        ];
    }

    protected function failedValidation(Validator $validator){
        $error = $validator->errors()->first();

        if($error){
            throw new HttpResponseException(Controller::giveResponse(null, Translator::traducirMensaje($error)));
        }

    }

}
