<?php

namespace App\Http\Translator;

class Translator
{
    /**
    * Traduce los mensajes de error en función de cual reciba.
    * 
    * @param  String $message
    * @return String $message
    */
    public static function traducirMensaje($message){
        $date = date("Y");
        $maxDate = date("Y") + 10;

        switch($message){

            /*
            * -------------------------------------
            *       Validaciones de Usuarios
            * -------------------------------------
            */

            // Name

            case "The name field is required.":
                return "El campo Nombre es un campo requerido";

            case "The name must not be greater than 100 characters.":
                return "El nombre no debe ser mayor de 100 caracteres";
            
            // Email

            case "The email field is required.":
                return "El campo Email es un campo requerido";

            case "The email must be a valid email address.":
                return "El formato del email introducido no es correcto";

            case "The email must not be greater than 100 characters.":
                return "El email no debe ser mayor de 100 caracteres";
                
            case "The email has already been taken.":
                return "Este email ya está registrado";
            
            // Password

            case "The password field is required.":
                return "El campo Contraseña es un campo requerido";

            case "The password must be at least 8 characters.":
                return "La contraseña debe tener al menos 8 caracteres";

            case "The password confirmation does not match.":
                return "La confirmación de contraseña no coincide";

            // Phone

            case "The phone must be a number.":
                return "El número de telefono ha de ser numérico";
    
            case "The phone must be 9 digits.":
                return "El número de telefono ha de tener 9 digitos";

            // Address

            case "The address field is required.":
                return "El campo Dirección es un campo requerido";

            case "The address must not be greater than 255 characters.":
                return "La dirección no debe ser mayor de 255 caracteres";

            /*
            * -------------------------------------
            *       Validaciones de Productos
            * -------------------------------------
            */
            
            // Name 

            case "The name must not be greater than 50 characters.":
                return "El nombre no debe ser mayor de 50 caracteres";

            // Description

            case "The description must not be greater than 500 characters.":
                return "La descripción no debe ser mayor de 500 caracteres";

            // Price
            case "The price field is required.":
                return "El campo Precio es un campo requerido";

            case "The price must be a number.":
                return "El campo Precio ha de ser numérico";

            case "The price must be at least 0.":
                return "El precio debe ser mayor que 0";

            case "The price must not be greater than 999999999.":
                return "El precio no puede ser mayor de 999999999";

            // Stock

            case "The stock field is required.":
                return "El campo Stock es un campo requerido";

            case "The stock must be a number.":
                return "El campo Stock ha de ser numérico";

            case "The stock must be at least 0.":
                return "El stock debe ser mayor que 0";

            case "The stock must not be greater than 9999.":
                return "El Stock no puede ser mayor de 9999";
            

            /*
            * -------------------------------------
            *       Validaciones de Reviews
            * -------------------------------------
            */

            // Comments

            case "The comments field is required.":
                return "Se requiere añadir un comentario a la review";

            case "The comments must not be greater than 255 characters.":
                return "El comentario no debe ser mayor de 255 caracteres";

            // Score

            case "The score field is required.":
                return "La puntuación es un campo requerido";

            case "The score must be a number.":
                return "La puntuación ha de ser numérica";

            case "The score must be at least 0.":
                return "La puntuación ha de ser entre 0 y 10";

            case "The score must not be greater than 10.":
                return "La puntuación ha de ser entre 0 y 10";

            /*
            * -------------------------------------
            *    Validaciones de Métodos de pago
            * -------------------------------------
            */
            
            // IBAN

            case "The iban field is required.":
                return "El campo IBAN es un campo requerido";

            case "The iban has already been taken.":
                return "La tarjeta ya está registrada";

            case "The iban must be a number.":
                return "El IBAN ha de ser numérico";

            case "The iban must be 16 digits.":
                return "El IBAN ha de tener 16 digitos";

            // CVV

            case "The cvv field is required.":
                return "El campo CVV es un campo requerido";

            case "The cvv must be a number.":
                return "El CVV ha de ser numérico";

            case "The cvv must be 3 digits.":
                return "El CVV ha de tener 3 digitos";

            // Expiration month

            case "The expiration month field is required.":
                return "El campo Fecha de Expedición es un campo requerido";
                
            case "The expiration month must be a number.":
                return "El campo Fecha de Expedición ha de ser numérico";

            case "The expiration month must be 2 digits.":
                return "El Mes de Expedición ha de tener 2 digitos";
            
            case "The expiration month must not be greater than 12.":
                return "El Mes de Expedición no puede ser mayor de 12";

            // Expiration year

            case "The expiration year field is required.":
                return "El campo Fecha de Expedición es un campo requerido";

            case "The expiration year must be a number.":
                return "El campo Fecha de Expedición ha de ser numérico";

            case "The expiration year must be 4 digits.":
                return "El Año de Expedición ha de tener 4 digitos";

            case "The expiration year must be at least $date.":
                return "El Año de Expedición ha de ser igual o posterior a $date";

            case "The expiration year must not be greater than $maxDate.":
                return "El Año de Expedición ha de ser igual o anterior a $maxDate";
            
                
            // Resto de mensajes

            default:
                return $message;

        }
  
    }
}