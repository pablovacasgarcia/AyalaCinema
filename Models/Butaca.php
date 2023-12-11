<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase Butaca que representa una butaca en un sistema de gestión de asientos.
 */
class Butaca
{
    /** @var int|null Identificador único de la butaca. Puede ser nulo si la butaca aún no tiene un ID asignado. */
    private int|null $id;

    /** @var int Número de fila de la butaca. */
    private int $fila;

    /** @var int Número de columna de la butaca. */
    private int $columna;

    /** @var BaseDatos Objeto para interactuar con la base de datos. */
    private BaseDatos $db;

    /**
     * Constructor de la clase Butaca.
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene el ID de la butaca.
     *
     * @return int|null ID de la butaca o nulo si no tiene asignado un ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Establece el ID de la butaca.
     *
     * @param int|null $id ID de la butaca o nulo si no tiene asignado un ID.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Obtiene el número de fila de la butaca.
     *
     * @return int Número de fila de la butaca.
     */
    public function getFila(): int
    {
        return $this->fila;
    }

    /**
     * Establece el número de fila de la butaca.
     *
     * @param int $fila Número de fila de la butaca.
     */
    public function setFila(int $fila): void
    {
        $this->fila = $fila;
    }

    /**
     * Obtiene el número de columna de la butaca.
     *
     * @return int Número de columna de la butaca.
     */
    public function getColumna(): int
    {
        return $this->columna;
    }

    /**
     * Establece el número de columna de la butaca.
     *
     * @param int $columna Número de columna de la butaca.
     */
    public function setColumna(int $columna): void
    {
        $this->columna = $columna;
    }

    /**
     * Obtiene todas las butacas de la base de datos.
     *
     * @return array de butacas obtenidas de la base de datos.
     */
    public function getAll(): array
    {
        $this->db->consulta("SELECT * FROM butacas");
        $butacas = $this->db->extraer_todos();
        $this->db->close();
        return $butacas;
    }
}
