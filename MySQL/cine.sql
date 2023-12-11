CREATE DATABASE IF NOT EXISTS cine;
SET NAMES UTF8MB4;
USE cine;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios(
    id              int(255) auto_increment not null,
    nombre          varchar(100) not null,
    apellidos       varchar(255),
    email           varchar(255) not null,
    password        varchar(255) not null,
    CONSTRAINT pk_usuarios PRIMARY KEY(id),
    CONSTRAINT uq_email UNIQUE(email)
    )ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

DROP TABLE IF EXISTS butacas;
CREATE TABLE IF NOT EXISTS butacas(
                                      id            int AUTO_INCREMENT PRIMARY KEY not null,
                                      fila          int not null,
                                      columna       int not null,
                                      reserva       varchar(255) not null
    )ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- Insertar 300 butacas con reserva en false
-- Crea una tabla temporal de números
CREATE TEMPORARY TABLE numeros (numero INT);

-- Llena la tabla con números del 1 al 300
INSERT INTO numeros (numero)
SELECT (a.N + b.N * 10 + c.N * 100) + 1 AS numero
FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
     (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
     (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c;
-- Inserta las butacas en la tabla butacas
INSERT INTO butacas (fila, columna, reserva)
SELECT
    (numero - 1) DIV 20 + 1 AS fila,
        (numero - 1) MOD 20 + 1 AS columna,
        'false' AS reserva
FROM numeros;
