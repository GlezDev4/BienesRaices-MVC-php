<?php 

namespace Controllers;
use MVC\Router;
use Model\Admin;

class LoginController{
    public static function login(Router $router){

        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $resultado = $auth = new Admin($_POST);

            $errores = $auth->validar();

            if(empty($errores)){
                // Autenticar el usuario existente
                $resultado = $auth->existeUsuario();

                if(!$resultado){
                    // Verificar si el usuario existe
                    $errores = Admin::getErrores();
                }else{
                    // Verificar el password
                    $autenticado = $auth->comprobarPassword($resultado);

                    if($autenticado){
                        // Autenticar al usuario
                        $auth->autenticar();
                    }else{
                        // Password Incorrecto (Mensaje de error)
                        $errores = Admin::getErrores();
                    }
                }
            }
        }
        
        $router->render('auth/login', [
            'errores' => $errores
        ]);
    }
    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }
}