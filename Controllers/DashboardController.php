<?php

namespace Controllers;

use Lib\Pages;

/**
 * Clase DashboardController que gestiona las acciones relacionadas con la página principal.
 */
class DashboardController
{
    /** @var Pages Objeto para gestionar las páginas. */
    private Pages $pages;

    /**
     * Constructor de la clase DashboardController.
     * Inicializa el objeto para gestionar las páginas.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Método principal que renderiza la página del panel de control.
     */
    public function main()
    {
        $this->pages->render('/dashboard/dashboard');
    }
}
