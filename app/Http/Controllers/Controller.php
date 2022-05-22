<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Convierte los datos que recibe en una respuesta JSON
     * 
     * @param  Illuminate\Database\Eloquent\Model $data
     * @param  String $message
     * @param  Integer $status
     * @return \Illuminate\Http\Response
     */
    public static function giveResponse($data, $message = null, $status = 200){

        return response()->json(['data' => $data, 'message' => $message], $status);
  
    }
}
