<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase Reserva que representa una reserva de butacas en un sistema de gestión de asientos.
 */
class Reserva
{
    /** @var array Lista de identificadores de butacas reservadas. */
    private array $butacas;

    /** @var string Nombre de usuario asociado a la reserva. */
    private string $usuario;

    /** @var BaseDatos Objeto para interactuar con la base de datos. */
    private BaseDatos $db;

    /**
     * Constructor de la clase Reserva.
     *
     * @param array $butacas Lista de identificadores de butacas reservadas.
     * @param string $usuario Nombre de usuario asociado a la reserva.
     */
    public function __construct(array $butacas = [], string $usuario)
    {
        $this->butacas = $butacas;
        $this->usuario = $usuario;
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene la lista de identificadores de butacas reservadas.
     *
     * @return array Lista de identificadores de butacas reservadas.
     */
    public function getButacas(): array
    {
        return $this->butacas;
    }

    /**
     * Establece la lista de identificadores de butacas reservadas.
     *
     * @param array $butacas Lista de identificadores de butacas reservadas.
     */
    public function setButacas(array $butacas): void
    {
        $this->butacas = $butacas;
    }

    /**
     * Obtiene el nombre de usuario asociado a la reserva.
     *
     * @return string Nombre de usuario asociado a la reserva.
     */
    public function getUsuario(): string
    {
        return $this->usuario;
    }

    /**
     * Establece el nombre de usuario asociado a la reserva.
     *
     * @param string $usuario Nombre de usuario asociado a la reserva.
     */
    public function setUsuario(string $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * Guarda la reserva en la base de datos asociando las butacas al usuario.
     *
     * @return bool True si la reserva se guardó correctamente, False si ocurrió un error.
     */
    public function guardarReserva(): bool
    {
        $butacas = $this->getButacas();
        $usuario = $this->getUsuario();
        try {
            foreach ($butacas as $butaca) {
                $stmt = $this->db->prepara("UPDATE butacas SET reserva=:usuario WHERE id=:id");
                $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindValue(':id', $butaca, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor();
            }
            $this->db->close();
            $result = true;
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }

    /**
     * Obtiene las butacas reservadas por el usuario.
     *
     * @return array|false Arreglo de butacas reservadas o False si ocurrió un error.
     */
    public function getReservas(): array|false
    {
        $usuario = $this->getUsuario();
        try {
            $consulta = $this->db->prepara("SELECT id, fila, columna FROM butacas WHERE reserva=:usuario");
            $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
            $consulta->execute();
            $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $err) {
            $result = false;
        }

        return $result;
    }

    /**
     * Anula la reserva de una butaca para el usuario.
     *
     * @param int $butaca Identificador de la butaca a anular.
     *
     * @return bool True si la anulación fue exitosa, False si ocurrió un error.
     */
    public function anularReserva(int $butaca): bool
    {
        $usuario = $this->getUsuario();
        try {
            $stmt = $this->db->prepara("UPDATE butacas SET reserva=\"false\" WHERE id=:id AND reserva=:usuario");
            $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindValue(':id', $butaca, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();

            $this->db->close();
            $result = true;
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }
}
