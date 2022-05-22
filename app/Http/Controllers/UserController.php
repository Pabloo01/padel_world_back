<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{   
     /**
     * Comprueba el email y la contraseña introducidos por el usuario y crea un token de acceso
     * 
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        
        if(!$email || !$password){
            return Controller::giveResponse(null, "No se ha introducido ningún email/contraseña");
        }

        $user = User::where('email', '=', $email)->first();

        if(!$user){
            return Controller::giveResponse(null, "El email introducido no está registrado");
        }

        if(!Hash::check($password, $user->password)){
            return Controller::giveResponse(null, "La contraseña introducida es incorrecta");
        }

        $token = $user->createToken('my_token')->plainTextToken;
        $user->save();
        $user->token = $token;

        return Controller::giveResponse(new UserResource($user), "Los datos son correctos");

    }

    /**
     * Crea un nuevo usuario con los datos introducidos por el usuario
     * 
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request)
    {
        $user = User::query()->create($request->toArray());

        $user->password = Hash::make($request->password);
        $token = $user->createToken('my_token')->plainTextToken;
        $user->save();
        $user->token = $token;

        return Controller::giveResponse(new UserResource($user), 'El usuario ha sido registrado con exito');
 
    }

    /**
     * Borra el token de acceso del usuario que se desloggea.
     * 
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
 
        return Controller::giveResponse(null,"Se ha cerrado sesion con exito");
    }

    /**
     * Comprueba los datos del formulario y edita el usuario.
     * 
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editUser(EditUserRequest $request)
    {
        $user = $request->user();

        if(!$user){

            return Controller::giveResponse(null,'El usuario no existe');
        }

        if($request->email != $user->email){
            $validator = Validator::make($request->all(),[
                'email' => Rule::unique(User::class) ,
            ]);

            if ($validator->fails()) {
                return Controller::giveResponse(null, "Este email ya está registrado");
            }

            $user->email = $request->email;
        }
        
        if($request->password || $request->oldPassword){

            if(!Hash::check($request->oldPassword, $user->password)){
            
                return Controller::giveResponse(null, "La contraseña introducida es incorrecta");
            }

            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        $user->save();
        $user->token = $request->bearerToken();

        return Controller::giveResponse(new UserResource($user), "Se ha actualizado correctamente el usuario");
    }

}