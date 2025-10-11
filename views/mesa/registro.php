<?php
require_once __DIR__ . '/../layouts/header.php';

// Cargar listas para el formulario
$pdo = Database::getInstance()->getConnection();
$delitos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
$zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();
?>
<h2>Registrar Nueva Incidencia</h2>

<form method="POST" enctype="multipart/form-data">
    <h3>Denunciante</h3>
    <label>Nombre completo: <input type="text" name="denunciante_nombre" required></label><br>
    <label>Sexo:
        <select name="denunciante_sexo" required>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select>
    </label><br>
    <label>DNI: <input type="text" name="denunciante_dni"></label><br>
    <label>Dirección: <input type="text" name="denunciante_direccion" required></label><br>
    <label>Teléfono: <input type="text" name="denunciante_telefono"></label><br>

    <h3>Agredido</h3>
    <label>
        <input type="radio" name="tipo_agredido" value="soy_yo" checked> Soy yo
    </label>
    <label>
        <input type="radio" name="tipo_agredido" value="otra_persona"> Otra persona
    </label>
    <div id="datos-agredido" style="display:none; margin-top: 10px; padding: 10px; border: 1px solid #ddd;">
        <label>Nombre completo: <input type="text" name="agredido_nombre"></label><br>
        <label>Sexo:
            <select name="agredido_sexo">
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
        </label><br>
        <label>DNI: <input type="text" name="agredido_dni"></label><br>
        <label>Dirección: <input type="text" name="agredido_direccion"></label><br>
        <label>Teléfono: <input type="text" name="agredido_telefono"></label><br>
    </div>

    <h3>Agresor (opcional)</h3>
    <label>Nombre completo: <input type="text" name="agresor_nombre"></label><br>
    <label>Sexo:
        <select name="agresor_sexo">
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select>
    </label><br>
    <label>DNI: <input type="text" name="agresor_dni"></label><br>
    <label>Dirección: <input type="text" name="agresor_direccion"></label><br>
    <label>Teléfono: <input type="text" name="agresor_telefono"></label><br>

    <h3>Detalles de la incidencia</h3>
    <label>Tipo de delito:
        <select name="tipo_delito" required>
            <option value="">Seleccionar</option>
            <?php foreach ($delitos as $d): ?>
                <option value="<?= htmlspecialchars($d['tipo_delito']) ?>"><?= htmlspecialchars($d['tipo_delito']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Clasificación:
        <select name="clasificacion_delito" required>
            <option value="">Seleccionar</option>
            <?php
            $stmt = $pdo->query("SELECT clasificacion FROM DelitosClasificaciones ORDER BY clasificacion");
            while ($row = $stmt->fetch()):
            ?>
                <option value="<?= htmlspecialchars($row['clasificacion']) ?>"><?= htmlspecialchars($row['clasificacion']) ?></option>
            <?php endwhile; ?>
        </select>
    </label><br>

    <label>Descripción: <textarea name="descripcion" required rows="4" style="width:100%;"></textarea></label><br>
    <label>Dirección de la incidencia: <input type="text" name="direccion_incidencia" required></label><br>
    <label>Latitud: <input type="number" step="any" name="latitud"></label><br>
    <label>Longitud: <input type="number" step="any" name="longitud"></label><br>
    <label>Zona:
        <select name="id_zona">
            <option value="">Seleccionar</option>
            <?php foreach ($zonas as $z): ?>
                <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Prioridad:
        <select name="prioridad" required>
            <option value="Baja">Baja</option>
            <option value="Media" selected>Media</option>
            <option value="Alta">Alta</option>
        </select>
    </label><br>
    <label>Evidencias (máx. 5 archivos): <input type="file" name="evidencias[]" multiple accept="image/*,video/*,audio/*"></label><br><br>

    <button type="submit">Registrar Incidencia</button>
</form>

<script src="/js/registro.js"></script>
<a href="/mesa/dashboard">← Volver al Dashboard</a>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>