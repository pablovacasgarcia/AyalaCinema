<?php
// Importar el espacio de nombres Utils
use Utils\Utils;

// Verificar si el registro se ha completado con éxito
if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'):
    ?>
    <!-- Mostrar mensaje de éxito si el registro se ha completado -->
    <strong class="mensaje">Registro completado correctamente</strong>
<?php
// Verificar si el registro ha fallado
elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'):
    ?>
    <!-- Mostrar mensaje de error si el registro ha fallado -->
    <strong class="error">No se ha podido registrar</strong>
<?php endif; ?>

<?php
// Eliminar la variable 'register' de la sesión después de mostrar el mensaje
Utils::deleteSession('register');
?>

<!-- Formulario de registro -->
<form action="<?= BASE_URL ?>usuario/registro/" method="POST">
    <label for="nombre">Nombre</label>
    <!-- Mostrar el valor del campo de nombre si existe en los datos proporcionados -->
    <input type="text" name="data[nombre]" id="nombre" <?php if (isset($datos['nombre'])): ?> value=<?=$datos['nombre']?>  <?php endif;?>>
    <?php
    // Mostrar mensaje de error específico para el campo de nombre
    if (isset($errores['nombre'])) : ?>
        <p class="error"><?= $errores['nombre']; ?></p>
    <?php endif; ?>

    <label for="apellidos">Apellidos</label>
    <!-- Mostrar el valor del campo de apellidos si existe en los datos proporcionados -->
    <input type="text" name="data[apellidos]" id="apellidos" <?php if (isset($datos['apellidos'])): ?> value=<?=$datos['apellidos']?>  <?php endif;?>>
    <?php
    // Mostrar mensaje de error específico para el campo de apellidos
    if (isset($errores['apellidos'])) : ?>
        <p class="error"><?= $errores['apellidos']; ?></p>
    <?php endif; ?>

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

    <input type="submit" value="Registrarse" required>
</form>
