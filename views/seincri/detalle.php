<?php
require_once __DIR__ . '/../layouts/header.php';

// Asegurar que $incidencia y $evidencias existen
if (!isset($incidencia) || !isset($evidencias)) {
    die("Error: Datos de incidencia no disponibles.");
}
?>
<h2>Detalle del Caso: <?= htmlspecialchars($incidencia['id_incidencia']) ?></h2>

<p><strong>Tipo de delito:</strong> <?= htmlspecialchars($incidencia['tipo_delito']) ?></p>
<p><strong>Clasificación:</strong> <?= htmlspecialchars($incidencia['clasificacion_delito'] ?? 'N/A') ?></p>
<p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($incidencia['descripcion'])) ?></p>
<p><strong>Dirección:</strong> <?= htmlspecialchars($incidencia['direccion_incidencia']) ?></p>
<p><strong>Estado actual:</strong> <span style="font-weight:bold; color:<?= $incidencia['estado'] === 'Resuelto' ? 'green' : ($incidencia['estado'] === 'Pendiente' ? 'red' : 'orange') ?>"><?= $incidencia['estado'] ?></span></p>
<p><strong>Prioridad:</strong> <?= $incidencia['prioridad'] ?></p>
<?php if (!empty($incidencia['zona_nombre'])): ?>
    <p><strong>Zona:</strong> <?= htmlspecialchars($incidencia['zona_nombre']) ?></p>
<?php endif; ?>

<h3>Denunciante</h3>
<p><strong>Nombre:</strong> <?= htmlspecialchars($incidencia['denunciante_nombre']) ?></p>
<?php if (!empty($incidencia['denunciante_dni'])): ?>
    <p><strong>DNI:</strong> <?= htmlspecialchars($incidencia['denunciante_dni']) ?></p>
<?php endif; ?>

<?php if (!empty($incidencia['agredido_nombre'])): ?>
    <h3>Agredido</h3>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($incidencia['agredido_nombre']) ?></p>
<?php endif; ?>

<?php if (!empty($incidencia['agresor_nombre'])): ?>
    <h3>Agresor</h3>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($incidencia['agresor_nombre']) ?></p>
<?php endif; ?>

<h3>Evidencias Adjuntas</h3>
<?php if (empty($evidencias)): ?>
    <p><em>No se han subido evidencias para este caso.</em></p>
<?php else: ?>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php foreach ($evidencias as $ev): ?>
            <div style="border: 1px solid #ccc; padding: 5px; text-align: center;">
                <p><?= ucfirst($ev['tipo_archivo']) ?></p>
                <?php if ($ev['tipo_archivo'] === 'foto'): ?>
                    <img src="/<?= htmlspecialchars($ev['ruta_archivo']) ?>" alt="Evidencia" style="max-width: 150px; max-height: 150px;">
                <?php else: ?>
                    <a href="/<?= htmlspecialchars($ev['ruta_archivo']) ?>" target="_blank" style="display: inline-block; margin-top: 5px;">
                        📄 Ver archivo
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Mapa (opcional) -->
<?php if (!empty($incidencia['latitud']) && !empty($incidencia['longitud'])): ?>
    <h3>Ubicación en el Mapa</h3>
    <div id="map" data-lat="<?= $incidencia['latitud'] ?>" data-lng="<?= $incidencia['longitud'] ?>" style="width: 100%; height: 300px; border: 1px solid #ccc;"></div>
    <!-- Incluir Google Maps API solo si usas mapas -->
    <script>
        function initMap() {
            const lat = parseFloat(document.getElementById('map').dataset.lat);
            const lng = parseFloat(document.getElementById('map').dataset.lng);
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: { lat: lat, lng: lng },
            });
            new google.maps.Marker({ position: { lat: lat, lng: lng }, map: map });
        }
    </script>
    <!-- Reemplaza TU_API_KEY por tu clave real de Google Maps -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap">
    </script>
<?php endif; ?>

<h3>Actualizar Estado del Caso</h3>
<form method="POST" style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
    <label for="estado"><strong>Nuevo estado:</strong></label><br>
    <select name="estado" id="estado" required style="padding: 5px; margin: 5px 0; width: 200px;">
        <?php
        $estados = ['Recibido', 'Investigando', 'Resuelto'];
        foreach ($estados as $estado) {
            // No permitir retroceder a "Recibido" si ya está más adelante
            if ($incidencia['estado'] === 'Pendiente' || $incidencia['estado'] === 'Recibido') {
                $disabled = ($estado === 'Recibido' && $incidencia['estado'] === 'Recibido') ? 'disabled' : '';
                echo "<option value=\"$estado\" $disabled>$estado</option>";
            } elseif ($incidencia['estado'] === 'Investigando') {
                $disabled = ($estado === 'Recibido') ? 'disabled' : '';
                echo "<option value=\"$estado\" $disabled>$estado</option>";
            } else { // Resuelto
                echo "<option value=\"Resuelto\" selected disabled>Resuelto</option>";
            }
        }
        ?>
    </select><br><br>

    <label for="notas"><strong>Notas (opcional):</strong></label><br>
    <textarea name="notas" id="notas" rows="3" style="width: 100%; padding: 5px;"></textarea><br><br>

    <button type="submit" style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Actualizar Estado
    </button>
</form>

<br>
<a href="/seincri/atencion" style="display: inline-block; margin-top: 10px;">← Volver a Atención de Casos</a>

<!-- Incluir JS global -->
<script src="/js/app.js"></script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>