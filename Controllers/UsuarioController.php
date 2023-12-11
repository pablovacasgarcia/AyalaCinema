<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;

/**
 * Controlador para la gestión de usuarios (registro, inicio de sesión, cierre de sesión).
 */
class UsuarioController
{
    /** @var Pages Instancia de la clase Pages para la gestión de páginas. */
    private Pages $pages;

    /**
     * Constructor de la clase UsuarioController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Maneja el proceso de registro de nuevos usuarios.
     */
    public function registro(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $_POST['data'];

                $usuario = Usuario::fromArray($registrado);

                $validacion=$usuario->validarRegistro();
                if ($validacion===true){
                    $usuario->setPassword(password_hash($registrado['password'], PASSWORD_BCRYPT, ['cost'=>4]));
                    $save = $usuario->create();
                    if ($save){
                        $_SESSION['register'] = "complete";
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                } else {
                    $errores=$validacion;
                    $_SESSION['register'] = "failed";
                }

            } else {
                $_SESSION['register'] = "failed";
            }
        }

        if (isset($errores)){
            $this->pages->render('/usuario/registro', ['datos'=>$registrado, 'errores'=>$errores]);
        } else {
            $this->pages->render('/usuario/registro');
        }

    }

    /**
     * Maneja el proceso de inicio de sesión de usuarios existentes.
     */
    public function login(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $login = $_POST['data'];

                $usuario = Usuario::fromArray($login);

                $validacion=$usuario->validarLogin();
                if ($validacion===true){
                    $verify = $usuario->login();

                    if ($verify!=false){
                        $_SESSION['login'] = $verify;
                        header('Location:'.BASE_URL);
                    } else {
                        $_SESSION['login'] = "failed";
                    }
                } else {
                    $errores=$validacion;
                    $_SESSION['login'] = "failed";
                }


            } else {
                $_SESSION['login'] = "failed";
            }
        }

        if (!isset($verify) OR !$verify){
            if (isset($errores)){
                $this->pages->render('/usuario/login', ['datos'=>$login, 'errores'=>$errores]);
            } else {
                if (isset($login)){
                    $this->pages->render('/usuario/login', ['datos'=>$login]);
                } else {
                    $this->pages->render('/usuario/login');
                }
            }
        }

    }

    /**
     * Cierra la sesión del usuario actual.
     */
    public function logout(){
        Utils::deleteSession('login');

        header("Location:".BASE_URL);
    }

}