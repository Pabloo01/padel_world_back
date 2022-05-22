<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Translator\Translator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewRequest extends FormRequest
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
            'score' => ['required', 'numeric', 'min:0','max:10'],
            'comments' => ['required','string','max:255']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();

        if($error){
            throw new HttpResponseException(Controller::giveResponse(null, Translator::traducirMensaje($error)));
        }
    }

}
