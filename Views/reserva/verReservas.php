<!-- Mostrar el título de la película y la información de la sala y hora -->
<h2 class="tituloPelicula">Lo imposible - Sala 4 18:30</h2>

<?php
// Verificar si hay un mensaje en la sesión
if (isset($_SESSION['mensaje'])):
    ?>
    <!-- Mostrar el mensaje y eliminarlo de la sesión -->
    <p class="mensaje"><?=$_SESSION['mensaje']?></p>
    <?php \Utils\Utils::deleteSession('mensaje')?>
<?php endif; ?>

<?php
// Verificar si hay reservas para mostrar
if (!empty($reservas)):
    ?>
    <!-- Iterar sobre las reservas y mostrar la información -->
    <?php foreach ($reservas as $reserva):?>
    <div class="reservas">
        <!-- Mostrar detalles de la reserva -->
        <p>Butaca número:<?=$reserva['id']?>    Fila:<?=$reserva['fila']?> Asiento:<?=$reserva['columna']?></p>

        <!-- Mostrar botón para anular la reserva con enlace correspondiente -->
        <button><a href="<?=BASE_URL?>reserva/anularReserva/?butaca=<?=$reserva['id']?>">Anular Reserva</a></button>
    </div>
<?php endforeach;?>

    <!-- Mostrar botón para generar PDF con enlace correspondiente -->
    <button><a href="<?=BASE_URL?>reserva/generarPdf/" target="_blank">Generar PDF<img src="../../Src/img/pdf.png"></a></button>
<?php else: ?>
    <!-- Mostrar mensaje de error si no hay reservas -->
    <p class="error">Aún no has hecho ninguna reserva</p>
<?php endif; ?>
