<?php

namespace Controllers;

/**
 * Clase FrontController que maneja las solicitudes y enruta hacia los controladores y acciones correspondientes.
 */
class FrontController
{
    /**
     * Método principal que maneja las solicitudes y enruta hacia los controladores y acciones correspondientes.
     */
    public static function main()
    {
        // Verifica si se proporciona un nombre de controlador en la solicitud.
        if (isset($_GET['controller'])) {
            $nombre_controlador = "Controllers\\" . $_GET["controller"] . "Controller";
        } else {
            // Si no se proporciona, utiliza el controlador predeterminado.
            $nombre_controlador = "Controllers\\" . CONTROLLER_DEFAULT;
        }

        // Verifica si la clase del controlador existe.
        if (class_exists($nombre_controlador)) {
            $controlador = new $nombre_controlador();

            // Verifica si se proporciona una acción en la solicitud y si el método existe en el controlador.
            if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $action = $_GET['action'];
                $controlador->$action();
            } elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
                // Si no se proporciona ni un controlador ni una acción, utiliza la acción predeterminada.
                $action_default = ACTION_DEFAULT;
                $controlador->$action_default();
            } else {
                // Si no se encuentra la acción, muestra un error 404.
                echo ErrorController::show_error404();
            }
        } else {
            // Si no se encuentra el controlador, muestra un error 404.
            echo ErrorController::show_error404();
        }
    }
}
