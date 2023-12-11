<?php

namespace Controllers;

/**
 * Clase ErrorController que gestiona las acciones relacionadas con los errores.
 */
class ErrorController
{
    /**
     * Muestra un mensaje de error 404 (página no encontrada).
     *
     * @return string Mensaje de error HTML indicando que la página no existe.
     */
    public static function show_error404(): string
    {
        return "<p class='error'>La página que buscas no existe</p>";
    }
}
