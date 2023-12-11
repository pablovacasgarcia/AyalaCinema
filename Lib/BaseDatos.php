<?php

namespace Lib;

use PDO;
use PDOException;

/**
 * Clase BaseDatos que proporciona métodos para interactuar con una base de datos MySQL.
 */
class BaseDatos
{
    /** @var PDO|null Objeto de conexión a la base de datos. */
    private $conexion;

    /** @var mixed Resultado de la última consulta ejecutada. */
    private mixed $resultado; //mixed novedad en PHP cualquier valor

    /** @var string Dirección del servidor de la base de datos. */
    private string $servidor;

    /** @var string Nombre de usuario para la conexión a la base de datos. */
    private string $usuario;

    /** @var string Contraseña para la conexión a la base de datos. */
    private string $pass;

    /** @var string Nombre de la base de datos. */
    private string $base_datos;

    /**
     * Constructor de la clase BaseDatos.
     * Configura la conexión a la base de datos utilizando variables de entorno.
     */
    public function __construct()
    {
        $this->servidor = $_ENV['DB_HOST'];
        $this->usuario = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->base_datos = $_ENV['DB_DATABASE'];
        $this->conexion = $this->conectar();
    }

    /**
     * Establece la conexión a la base de datos.
     *
     * @return PDO Objeto de conexión a la base de datos.
     * @throws PDOException Si hay un error al intentar conectarse a la base de datos.
     */
    private function conectar(): PDO
    {
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES Utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );

            $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}", $this->usuario, $this->pass, $opciones);
            return $conexion;
        } catch (\PDOException $e) {
            echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Ejecuta una consulta SQL en la base de datos.
     *
     * @param string $consultaSQL Consulta SQL a ejecutar.
     */
    public function consulta(string $consultaSQL): void
    {
        $this->resultado = $this->conexion->query($consultaSQL);
    }

    /**
     * Obtiene el próximo registro del conjunto de resultados como un array asociativo.
     *
     * @return mixed Array asociativo con los datos del registro o False si no hay más registros.
     */
    public function extraer_registro(): mixed
    {
        return ($fila = $this->resultado->fetch(PDO::FETCH_ASSOC)) ? $fila : false;
    }

    /**
     * Obtiene todos los registros del conjunto de resultados como un array de arrays asociativos.
     *
     * @return array Array de arrays asociativos con los datos de los registros.
     */
    public function extraer_todos(): array
    {
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el número de filas afectadas por la última operación.
     *
     * @return int Número de filas afectadas.
     */
    public function filasAfectadas(): int
    {
        return $this->resultado->rowCount();
    }

    /**
     * Cierra la conexión a la base de datos.
     */
    public function close()
    {
        if ($this->conexion !== null) {
            $this->conexion = null;
        }
    }

    /**
     * Prepara una sentencia SQL para su ejecución y devuelve un objeto de sentencia.
     *
     * @param string $pre Sentencia SQL a preparar.
     *
     * @return mixed Objeto de sentencia PDO.
     */
    public function prepara($pre)
    {
        return $this->conexion->prepare($pre);
    }
}
