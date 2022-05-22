<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Http\Requests\FamilyRequest;
use App\Http\Resources\FamilyResource;
use App\Http\Resources\ProductResource;

class FamilyController extends Controller
{

    /**
     * Devuelve todas las familias
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAllFamilies()
    {
        $families = FamilyResource::collection(Family::all());

        foreach($families as $family){
            $family->numProducts = $family->products()->count();
        }
        
        return Controller::giveResponse($families);
    }

    /**
     * Devuelve todos los productos de una familia concreta.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function getFamilysProducts($id)
    {
        $family = Family::find($id);

        if(!$family){
            return Controller::giveResponse(null,'La familia seleccionada no existe', 404);
        }

        return Controller::giveResponse(ProductResource::collection($family->products),null);
    }

    /**
     * Crea una familia nueva con los datos de la petición.
     * 
     * @param  App\Http\Requests\FamilyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createFamily(FamilyRequest $request)
    {

        $family = Family::query()->create($request->toArray());

        return Controller::giveResponse(new FamilyResource($family),'La familia ha sido creada correctamente');
    }

    /**
     * Modifica una familia con los datos de la petición.
     * 
     * @param  App\Http\Requests\FamilyRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFamily($id, FamilyRequest $request)
    {

        $family = Family::find($id);

        if(!$family){
            return Controller::giveResponse(null,'La familia seleccionada no existe');
        }

        $family->update($request->toArray());
        $family->save();

        return Controller::giveResponse(new FamilyResource($family),'La familia ha sido modificada correctamente');
    }

    /**
     * Borra la familia con la id que recibe.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFamily($id)
    {
        $family = Family::find($id);

        if(!$family){
            return Controller::giveResponse(null,'La familia seleccionada no existe');
        }

        $family->delete();

        return Controller::giveResponse(new FamilyResource($family),'La familia ha sido borrada correctamente');
    }

}