<!-- Mostrar el título de la película y la información de la sala y hora -->
<h2 class="tituloPelicula">Lo imposible - Sala 4 18:30</h2>

<?php
// Importar el controlador de Butacas
use Controllers\ButacaController;

// Verificar si existe una sesión para butacas seleccionadas
if (isset($_SESSION['seleccionadas'])){
    // Deserializar las butacas seleccionadas almacenadas en la sesión
    $seleccionadas = unserialize($_SESSION['seleccionadas']);
} else {
    // Si no hay butacas seleccionadas, inicializar un array vacío
    $seleccionadas = [];
}

// Obtener todas las butacas disponibles a través del controlador
$butacas = ButacaController::obtenerButacas();

// Verificar si hay errores almacenados en la sesión
if (isset($_SESSION['errores'])):
    ?>
    <!-- Mostrar mensajes de error -->
    <p class="error"><?=$_SESSION['errores']?></p>
    <?php
    // Eliminar los errores de la sesión después de mostrarlos
    \Utils\Utils::deleteSession('errores');
endif;
?>

<!-- Crear una tabla para mostrar las butacas -->
<table>
    <?php
    // Iterar sobre las filas de la sala (15 filas)
    for ($counter = 0, $i = 0; $i < 15; $i++):
        ?>
        <tr>
            <!-- Mostrar el número de fila -->
            <td><?=$i + 1?></td>

            <?php
            // Iterar sobre las columnas de la sala (20 columnas)
            for ($j = 0; $j < 20; $j++, $counter++):
                ?>
                <td class="butacas">
                    <?php
                    // Verificar si la butaca está reservada
                    if ($butacas[$counter]['reserva'] !== "false"):
                        ?>
                        <!-- Mostrar una butaca roja si está reservada -->
                        <img src="../../Src/img/ButacaRoja.png" class="butaca">
                    <?php else: ?>
                        <?php
                        // Verificar si la butaca está en el array de butacas seleccionadas
                        if (in_array($butacas[$counter]['id'], $seleccionadas)):
                            ?>
                            <!-- Mostrar una butaca gris si está seleccionada -->
                            <a href="<?=BASE_URL?>reserva/seleccionar/?butaca=<?=$butacas[$counter]['id']?>"><img src="../../Src/img/ButacaGris.png" class="butaca"></a>
                        <?php else: ?>
                            <!-- Mostrar una butaca verde si no está seleccionada -->
                            <a href="<?=BASE_URL?>reserva/seleccionar/?butaca=<?=$butacas[$counter]['id']?>"><img src="../../Src/img/ButacaVerde.png" class="butaca"></a>
                        <?php endif;?>
                    <?php endif;?>

                    <!-- Mostrar información de fila y asiento -->
                    <p class="asiento">Fila: <?=$butacas[$counter]['fila']?>  Asiento: <?=$butacas[$counter]['columna']?></p>
                </td>
            <?php endfor;?>
        </tr>
    <?php endfor;?>

    <!-- Mostrar números de columna en la última fila -->
    <tr>
        <td></td>
        <?php for ($j = 0; $j < 20; $j++):?>
            <td><?=$j + 1?></td>
        <?php endfor;?>
    </tr>
</table>

<?php
// Mostrar el botón de reserva si hay butacas seleccionadas
if (count($seleccionadas) > 0):
    ?>
    <button><a href="<?=BASE_URL?>reserva/guardar/">Reservar</a></button>
<?php endif;?>
