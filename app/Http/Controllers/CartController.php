<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Devuelve todos los productos en el carrito del usuario
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllCartItems($id)
    {
        $conditions = ["user_id"=>$id,"sold"=>0];
        $cartItems = CartItemResource::collection(CartItem::where($conditions)->get());
        
        return Controller::giveResponse($cartItems);
    }

    
    /**
     * Comprueba si un producto ya ha sido agregado al carrito.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkItemIsInCart(Request $request)
    {   
        $conditions = ["user_id"=>$request->user_id,"product_id"=>$request->product_id,"sold"=>0];
        $cartItems = CartItem::where($conditions)->count();

        return Controller::giveResponse($cartItems == 1);
    }

    /**
     * Añade un producto al carrito.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCartItem(Request $request)
    {
        $cartItem = CartItem::query()->create($request->toArray());
        
        return Controller::giveResponse(new CartItemResource($cartItem), "Articulo añadido a la cesta");
    }

    /**
     * Modifica un producto del carrito.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCartItem(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if(!$cartItem){
            return Controller::giveResponse(null,'La articulo seleccionado no está en el carrito');
        }

        $cartItem->update($request->toArray());
        
        return Controller::giveResponse(new CartItemResource($cartItem), "El articulo ha sido modificado correctamente");
    }

    /**
     * Modifica todos los productos del carrito.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCartItems(Request $request)
    {    
        
        $cartItems = $request->toArray();
        $cuantities = array_column($cartItems,'cuantity');
        $idItems = array_column($cartItems,'id');

        
        for($i = 0; $i<count($cartItems) - 1; $i++){
            $item = CartItem::find($idItems[$i]);
            $item->cuantity = $cuantities[$i];
            $item->save();
        }

        return Controller::giveResponse($cartItems);
 
    }

    /**
     * Elimina un producto del carrito.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCartItem($id)
    {
        $cartItem = CartItem::find($id);

        if(!$cartItem){
            return Controller::giveResponse(null,'La articulo seleccionado no está en el carrito');
        }

        $cartItem->delete();
        
        return Controller::giveResponse(new CartItemResource($cartItem), "El articulo ha sido eliminado del carrito correctamente");
    }

    /**
     * Marca como comprados todos los items que estaban en el carrito y actualiza el stock de los productos 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buyCartItems(Request $request)
    {
        $cartItems = $request->toArray();
        $cuantities = array_column($cartItems,'cuantity');
        $idItems = array_column($cartItems,'id');
        
        $products = array();
        $items = array();
        
        for($i = 0; $i<count($cartItems) - 1; $i++){
            $ids = array_column($cartItems[$i],'id');
            $productId = $ids[1];
            $product = Product::find($productId);
            $product->stock = $product->stock - $cuantities[$i];

            if($product->stock < 0){
                $message = "No se ha podido completar la compra. No hay suficiente stock de $product->name";
                return Controller::giveResponse(null,$message);
            }

            $products[] = $product;
            $items[] = CartItem::find($idItems[$i]);

        }

        foreach($products as $product){
            $product->update();
        }

        foreach($items as $item){
            $item->sold = 1;
            date_default_timezone_set('Europe/Madrid');
            $item->purchase_date = date('y-m-d H:i:s', time());
            $item->update();
        }

        return Controller::giveResponse($cartItems,'Productos comprados con exito');
        
    }

    /**
     * Recupera los productos comprados por el usuario y los ordena por la fecha de compra en orden decendente.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrders($id)
    {
        $conditions = ["user_id"=>$id,"sold"=>1];
        $cartItems = CartItemResource::collection(CartItem::where($conditions)->get());

        return Controller::giveResponse($cartItems);
    }
}