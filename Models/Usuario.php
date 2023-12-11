<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase Usuario que representa a un usuario en un sistema de gestión de usuarios.
 */
class Usuario
{
    /** @var string|null Identificador único del usuario. Puede ser nulo si el usuario aún no tiene un ID asignado. */
    private string|null $id;

    /** @var string Nombre del usuario. */
    private string $nombre;

    /** @var string Apellidos del usuario. */
    private string $apellidos;

    /** @var string Email del usuario. */
    private string $email;

    /** @var string Contraseña del usuario. */
    private string $password;

    /** @var BaseDatos Objeto para interactuar con la base de datos. */
    private BaseDatos $db;

    /**
     * Constructor de la clase Usuario.
     *
     * @param string|null $id Identificador único del usuario. Puede ser nulo si el usuario aún no tiene un ID asignado.
     * @param string $nombre Nombre del usuario.
     * @param string $apellidos Apellidos del usuario.
     * @param string $email Email del usuario.
     * @param string $password Contraseña del usuario.
     */
    public function __construct(string|null $id, string $nombre, string $apellidos, string $email, string $password)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene el identificador único del usuario.
     *
     * @return string|null Identificador único del usuario. Puede ser nulo si el usuario aún no tiene un ID asignado.
     */
    public function getId(): string|null
    {
        return $this->id;
    }

    /**
     * Establece el identificador único del usuario.
     *
     * @param string $id Identificador único del usuario. Puede ser nulo si el usuario aún no tiene un ID asignado.
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string Nombre del usuario.
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $nombre Nombre del usuario.
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtiene los apellidos del usuario.
     *
     * @return string Apellidos del usuario.
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * Establece los apellidos del usuario.
     *
     * @param string $apellidos Apellidos del usuario.
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * Obtiene el email del usuario.
     *
     * @return string Email del usuario.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Establece el email del usuario.
     *
     * @param string $email Email del usuario.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Obtiene la contraseña del usuario.
     *
     * @return string Contraseña del usuario.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Establece la contraseña del usuario.
     *
     * @param string $password Contraseña del usuario.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Obtiene el rol del usuario.
     *
     * @return string Rol del usuario.
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * Establece el rol del usuario.
     *
     * @param string $rol Rol del usuario.
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * Crea un objeto Usuario a partir de un array de datos.
     *
     * @param array $data Array de datos del usuario.
     *
     * @return Usuario Objeto Usuario creado a partir del array de datos.
     */
    public static function fromArray(array $data): Usuario
    {
        return new Usuario(
            $data['id'] ?? null,
            $data['nombre'] ?? '',
            $data['apellidos'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? '',
        );
    }

    /**
     * Desconecta el objeto de la base de datos cerrando la conexión.
     */
    public function desconecta(): void
    {
        $this->db->close();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @return bool True si la creación fue exitosa, False si ocurrió un error.
     */
    public function create(): bool
    {
        $id = NULL;
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password) values (:id, :nombre, :apellidos, :email, :password)");

            $ins->bindValue(':id', $id, PDO::PARAM_INT);
            $ins->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $ins->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
            $ins->bindValue(':email', $email, PDO::PARAM_STR);
            $ins->bindValue(':password', $password, PDO::PARAM_STR);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;
        $this->db->close();

        return $result;
    }

    /**
     * Realiza el proceso de inicio de sesión para el usuario.
     *
     * @return mixed Devuelve el objeto Usuario si el inicio de sesión fue exitoso, False si no fue exitoso.
     */
    public function login(): mixed
    {
        $email = $this->getEmail();
        $password = $this->getPassword();

        try {
            $datosUsuario = $this->buscaMail($email);

            if ($datosUsuario) {
                $verify = password_verify($password, $datosUsuario->password);

                if ($verify) {
                    $result = $datosUsuario;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (PDOException $error) {
            $result = false;
        }

        return $result;
    }

    /**
     * Busca un usuario en la base de datos por su email.
     *
     * @param string $email Email del usuario a buscar.
     *
     * @return mixed Devuelve el objeto Usuario si se encuentra, False si no se encuentra.
     */
    public function buscaMail($email): mixed
    {
        try {
            $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
            $select->bindValue(':email', $email, PDO::PARAM_STR);
            $select->execute();
            if ($select && $select->rowCount() == 1) {
                $result = $select->fetch(PDO::FETCH_OBJ);
            } else {
                $result = false;
            }
        } catch (PDOException $err) {
            $result = false;
        }
        return $result;
    }

    /**
     * Valida los datos del usuario durante el proceso de registro.
     *
     * @return array|bool Array de errores si los hay, True si no hay errores.
     */
    public function validarRegistro(): array|bool
    {
        // Sanear datos
        $this->nombre = filter_var($this->nombre, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->apellidos = filter_var($this->apellidos, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->password = filter_var($this->password, FILTER_SANITIZE_SPECIAL_CHARS);

        $errores = [];

        // Validación del nombre
        if (empty($this->nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]+$/', $this->nombre)) {
            $errores['nombre'] = 'El nombre contiene caracteres no permitidos.';
        }

        // Validación de apellidos
        if (empty($this->apellidos)) {
            $errores['apellidos'] = 'Los apellidos son obligatorios.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]+$/', $this->apellidos)) {
            $errores['apellidos'] = 'Los apellidos contienen caracteres no permitidos.';
        }

        // Validación del email
        if (empty($this->email)) {
            $errores['email'] = 'El email es obligatorio.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato del email no es válido.';
        } elseif ($this->buscaMail($this->email) !== false) {
            $errores['email'] = 'Este correo ya ha sido registrado.';
        }

        // Validación de la contraseña
        if (empty($this->password)) {
            $errores['password'] = 'La contraseña es obligatoria.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $this->password)) {
            $errores['password'] = 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número, y tener al menos 8 caracteres.';
        }

        // Devuelve el array de errores si los hay, o true si no hay errores
        return empty($errores) ? true : $errores;
    }

    /**
     * Valida los datos del usuario durante el proceso de inicio de sesión.
     *
     * @return array|bool Array de errores si los hay, True si no hay errores.
     */
    public function validarLogin(): array|bool
    {
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->password = filter_var($this->password, FILTER_SANITIZE_SPECIAL_CHARS);

        $errores = [];

        // Validación del email
        if (empty($this->email)) {
            $errores['email'] = 'El email es obligatorio.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El formato del email no es válido.';
        } elseif ($this->buscaMail($this->email) == false) {
            $errores['email'] = 'Este correo no pertenece a ninguna cuenta.';
        }

        // Validación de la contraseña
        if (empty($this->password)) {
            $errores['password'] = 'La contraseña es obligatoria.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $this->password)) {
            $errores['password'] = 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número, y tener al menos 8 caracteres.';
        }

        // Devuelve el array de errores si los hay, o true si no hay errores
        return empty($errores) ? true : $errores;
    }
}
