<?php

namespace Controllers;

use Classes\Email;
use Model\User;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $user = User::where('email', $auth->email);

                if ($user) {
                    // Verificar el password
                    if ($user->comprobarPasswordAndVerificado($auth->password)) {
                        // autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $user->id;
                        $_SESSION['nombre'] = $user->name . " " . $user->surname;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento
                        if($user->admin === "1") {
                            $_SESSION['admin'] = $user->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /reservation');
                        }

                    }

                }else {
                    User::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = User::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);

    }
    public static function logout() {
        echo "Desde Logout";
    }
    public static function forgot(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new User($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $user = User::where('email', $auth->email);

                if($user && $user->confirmed === "1") {
                    //Generar un token
                    $user->crearToken();
                    $user->guardar();
                    // Enviar el email
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->enviarInstrucciones();

                    User::setAlerta('success', 'Check your email');
                }else {
                    User::setAlerta('error', 'This user does not exist or is not confirmed'); 
                }
            }
        }
        $alertas = User::getAlertas();

        $router->render('auth/forgot-password',[
            'alertas'=>$alertas
        ]);
    }
    public static function recover(Router $router) {
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        // Buscar usuario por su token
        $user = User::where('token', $token);

        if(empty($user)) {
            User::setAlerta('error', 'This Token is not valid');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new User($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $user->password = null;
                $user->password = $password->password;
                $user->hashPassword();
                $user->token = null;

                $resultado = $user->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = User::getAlertas();
        $router->render('auth/recover-password', [
            'alertas'=>$alertas, 
            'error'=>$error
        ]);
    }
    public static function create(Router $router) {
        $user = new User($_POST);

        //Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->sincronizar($_POST);
            $alertas = $user->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if (empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $user->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = User::getAlertas();
                } else {
                    // Hashear el password
                    $user->hashPassword();

                    //Generar un token unico
                    $user->crearToken();

                    // Enviar el Email de confirmacion
                    $email = new Email($user->name, $user->email, $user->token);

                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $user->guardar();
                    if($resultado) {
                        header('Location: /message');
                    }
                }
            }
        }

        $router->render('auth/create-account', [
            'user' => $user,
            'alertas' => $alertas
        ]);
    }

    public static function message(Router $router) {
        $router->render('auth/message');
    }
    public static function confirm(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $user = User::where('token', $token);

        if(empty($user)) {
            //mostrar mensaje de error
            User::setAlerta('error', 'This token is not valid');

        } else  {
            // modificar el usuario confirmado
            $user->confirmed = "1";
            $user->token = '';
            $user->guardar();
            User::setAlerta('success', 'Your account was confirmed correctly');
        }

        $alertas = User::getAlertas();
        $router->render('auth/confirm-account', [
            'alertas' => $alertas
        ]);
    }
}