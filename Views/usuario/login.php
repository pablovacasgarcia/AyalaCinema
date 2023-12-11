<?php
// Importar el espacio de nombres Utils
use Utils\Utils;

// Verificar si hay un intento de inicio de sesión fallido en la sesión
if(isset($_SESSION['login']) && $_SESSION['login'] == 'failed'):
    ?>
    <!-- Mostrar mensaje de error si el inicio de sesión ha fallado -->
    <strong class="error">No se ha podido iniciar sesión</strong>

    <?php
    // Eliminar la variable 'login' de la sesión después de mostrar el mensaje de error
    Utils::deleteSession('login');
    ?>
<?php endif; ?>

<?php
// Verificar si hay errores almacenados en la sesión
if (isset($_SESSION['errores'])):
    ?>
    <!-- Mostrar mensajes de error generales -->
    <p class="error"><?=$_SESSION['errores']?></p>

    <?php
    // Eliminar la variable 'errores' de la sesión después de mostrar los mensajes de error
    Utils::deleteSession('errores');
endif;
?>

<?php
// Verificar si no hay sesión activa o si el inicio de sesión ha fallado
if(!isset($_SESSION['login']) OR $_SESSION['login'] == 'failed'):
    ?>
    <!-- Formulario de inicio de sesión -->
    <form action="<?=BASE_URL?>usuario/login/" method="POST">
        <label for="email">Email</label>
        <!-- Mostrar el valor del campo de email si existe en los datos proporcionados -->
        <input type="text" name="data[email]" id="email" <?php if (isset($datos['email'])): ?> value=<?=$datos['email']?>  <?php endif;?>>

        <?php
        // Mostrar mensaje de error específico para el campo de email
        if (isset($errores['email'])) : ?>
            <p class="error"><?= $errores['email']; ?></p>
        <?php endif; ?>

        <label for="password">Contraseña</label>
        <!-- Mostrar el valor del campo de contraseña si existe en los datos proporcionados -->
        <input type="password" name="data[password]" id="password" <?php if (isset($datos['password'])): ?> value=<?=$datos['password']?>  <?php endif;?>>

        <?php
        // Mostrar mensaje de error específico para el campo de contraseña
        if (isset($errores['password'])) : ?>
            <p class="error"><?= $errores['password']; ?></p>
        <?php endif; ?>

        <input type="submit" value="Login" required>
    </form>
<?php endif;?>
