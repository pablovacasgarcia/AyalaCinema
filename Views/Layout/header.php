<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ayala Cinema</title>
    <link rel="stylesheet" href="../../Src/css/style.css" type="text/css">
</head>
<body>
    <header>
        <h1><a href="<?=BASE_URL?>">Ayala Cinema</a></h1>
        <?php if (isset($_SESSION['login']) AND $_SESSION['login']!='failed'):?>
            <h2><?=$_SESSION['login']->nombre?> <?=$_SESSION['login']->apellidos?></h2>
        <?php endif;?>
        <nav>
            <?php if (!isset($_SESSION['login']) OR $_SESSION['login']=='failed'):?>
                <a href="<?=BASE_URL?>usuario/login/">Identificarse</a>
                <a href="<?=BASE_URL?>usuario/registro/">Registrarse</a>
            <?php else:?>
                <a href="<?=BASE_URL?>reserva/verReservas/">Reservas</a>
                <a href="<?=BASE_URL?>usuario/logout/">Cerrar Sesión</a>
            <?php endif;?>
        </nav>
    </header>

