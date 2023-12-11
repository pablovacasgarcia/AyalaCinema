<?php

namespace Controllers;

use Lib\Pages;
use Models\Butaca;

/**
 * Clase ButacaController que gestiona las acciones relacionadas con las butacas.
 */
class ButacaController
{
    /** @var Pages Objeto para gestionar las páginas. */
    private Pages $pages;

    /**
     * Constructor de la clase ButacaController.
     * Inicializa el objeto para gestionar las páginas.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Obtiene todas las butacas disponibles.
     *
     * @return array con todas las butacas disponibles.
     */
    public static function obtenerButacas(): array
    {
        $butaca = new Butaca();
        return $butaca->getAll();
    }
}
