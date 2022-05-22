<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{

    /**
     * Devuelve todos los productos
     * 
     * @return \Illuminate\Http\Response
     */
    public static function getAllProducts()
    {

        $products = ProductResource::collection(Product::all());

        return Controller::giveResponse($products, null);
    }

    /**
     * Busca un producto concreto por la id que recibe.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public static function getProductById($id){
        $product = Product::find($id);

        if($product){
            return Controller::giveResponse(new ProductResource($product), null);
        } else {
            return Controller::giveResponse(null, "No se ha encontrado el producto");
        }
 
    }
    
    /**
     * Crea un producto nuevo con los datos de la petición.
     * 
     * @param  App\Http\Requests\ProductsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createProduct(ProductsRequest $request)
    {
        if($request->family_id == "null"){
            
            return Controller::giveResponse(null,'No se ha seleccionado ninguna familia.');
        }

        if($request->description == "null"){
            $request->description = null;
        }

        $array = array(
            "name" => $request->name,
            "description" => $request->description,
            "family_id" => $request->family_id,
            "stock" => $request->stock,
            "price" => $request->price
        );

        $product = Product::query()->create($array);

        if($request->image != "null" && $request->image != "undefined"){
            $base64 = $request->image;
            $storage_path = storage_path('app/public/images');
            $destinationPath = "$storage_path/";

            $fileName = $product->name . '_' . time() . '.png';

            file_put_contents($destinationPath.$fileName, file_get_contents($base64));
 
            $url = str_replace('/home/forge/', 'https://' ,$destinationPath.$fileName);

            $product->image = str_replace('app/public/','',$url);
        }

        $product->save();

        return Controller::giveResponse(new ProductResource($product),'Se ha creado el producto correctamente');
    }

    /**
     * Modifica un producto con los datos de la petición.
     * 
     * @param  App\Http\Requests\ProductsRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(ProductsRequest $request, $id)
    {
        $product = Product::find($id);

        if(!$product){
            return Controller::giveResponse(null,'El producto seleccionado no existe', 404);
        }

        if($request->family_id == "null"){
            return Controller::giveResponse(null,'No se ha seleccionado ninguna familia.');
        }

        if($request->description == "null"){
            $request->description = null;
        }

        
        $array = array(
            "name" => $request->name,
            "description" => $request->description,
            "family_id" => $request->family_id,
            "stock" => $request->stock,
            "price" => $request->price
        );
        
        $product->update($array); 

        if($request->image != "null" && $request->image != "undefined"){
            $storage_path = storage_path('app/public/images');
            if($product->image){
                $filePath = $storage_path.substr($product->image, strrpos($product->image,'/'));
    
                if(File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            $base64 = $request->image;
            $destinationPath = "$storage_path/";

            $fileName = $product->name . '_' . time() . '.png';

            file_put_contents($destinationPath.$fileName, file_get_contents($base64));
 
            $url = str_replace('/home/forge/', 'https://' ,$destinationPath.$fileName);

            $product->image = str_replace('app/public/','',$url);
        }

        $product->save();

        return Controller::giveResponse(new ProductResource($product),'El producto ha sido modificado correctamente');
    }

    /**
     * Borra el producto con la id que recibe.
     * 
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if(!$product){
            return Controller::giveResponse(null,'El producto seleccionado no existe');
        }

        if($product->image){
            $storage_path = storage_path('app/public/images');
            $filePath = $storage_path.substr($product->image, strrpos($product->image,'/'));

            if(File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        
        $product->delete();
        
        return Controller::giveResponse(new ProductResource($product),'El producto ha sido borrado correctamente');
    }
    
}