<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Translator\Translator;
use App\Models\PaymentMethod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
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
            'iban' => ['required','numeric','digits:16',Rule::unique(PaymentMethod::class)],
            'cvv' => ['required','numeric', 'digits:3'],
            'expiration_month' => ['required','numeric','min:1','max:12'],
            'expiration_year' => ['required','numeric', 'digits:4', 'min:'.date("Y"), 'max:'.date("Y") + 10]
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
