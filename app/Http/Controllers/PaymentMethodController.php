<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPaymentMethodRequest;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /**
     * Devuelve todos los productos en el carrito del usuario
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaymentMethod($id)
    {
        $payment = PaymentMethodResource::collection(PaymentMethod::where("user_id", $id)->get());
        
        return Controller::giveResponse($payment);
    }

    /**
     * Devuelve todos los productos en el carrito del usuario
     * 
     * @param  App\Http\Requests\PaymentMethodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaymentMethod(PaymentMethodRequest $request)
    {
        $payment = PaymentMethod::query()->create($request->toArray());

        
        return Controller::giveResponse(new PaymentMethodResource($payment));
    }

    /**
     * Modifica el método de pago correspondiente con los datos que se le envia.
     * 
     * @param  App\Http\Requests\EditPaymentMethodRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentMethod(EditPaymentMethodRequest $request, $id)
    {

        $payment = PaymentMethod::find($id);

        if($payment){
            Controller::giveResponse(null, "El método de pago seleccionado no existe");
        }

        if($request->iban != $payment->iban){
            $validator = Validator::make($request->all(),[
                'iban' => Rule::unique(PaymentMethod::class) ,
            ]);

            if ($validator->fails()) {
                return Controller::giveResponse(null, "Este iban ya está registrado");
            }

            $payment->iban = $request->iban;
        }

        $payment->name = $request->name;
        $payment->cvv = $request->cvv;
        $payment->expiration_month = $request->expiration_month;
        $payment->expiration_year = $request->expiration_year;

        $payment->save();

        return Controller::giveResponse(new PaymentMethodResource($payment), "Se ha actualizado correctamente el método de pago");
    }

    /**
     * Elimina el método de pago correspondiente a la id que se le envia.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePaymentMethod($id)
    {
        $payment = PaymentMethod::find($id);

        if($payment){
            Controller::giveResponse(null, "El método de pago seleccionado no existe");
        }

        $payment->delete();

        return Controller::giveResponse(new PaymentMethodResource($payment), "El método de pago ha sido eliminado con éxito");
    }
}
