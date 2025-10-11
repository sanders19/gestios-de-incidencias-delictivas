<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Incidencias</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/registro.css">
</head>
<body>
<?php
require_once __DIR__ . '/../layouts/header.php';

// Cargar datos para el formulario
$pdo = Database::getInstance()->getConnection();

// Obtener todos los delitos con clasificaciones
$delitosClasificaciones = [];
$stmt = $pdo->query("SELECT tipo_delito, clasificacion FROM DelitosClasificaciones ORDER BY tipo_delito, clasificacion");
while ($row = $stmt->fetch()) {
    $delitosClasificaciones[] = $row;
}

// Obtener tipos únicos de delito
$tiposUnicos = array_unique(array_column($delitosClasificaciones, 'tipo_delito'));

// Obtener zonas
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

    <div id="datos-agredido" style="display:none; margin-top: 15px; padding: 15px; border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">
        <h4>Datos de la otra persona</h4>
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
        <select name="tipo_delito" id="tipo-delito" required>
            <option value="">Seleccionar tipo de delito</option>
            <?php foreach ($tiposUnicos as $tipo): ?>
                <option value="<?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars($tipo) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Clasificación:
        <select name="clasificacion_delito" id="clasificacion-delito" required>
            <option value="">Seleccionar clasificación</option>
            <?php foreach ($delitosClasificaciones as $dc): ?>
                <option value="<?= htmlspecialchars($dc['clasificacion']) ?>" 
                        data-tipo="<?= htmlspecialchars($dc['tipo_delito']) ?>" 
                        style="display:none;">
                    <?= htmlspecialchars($dc['clasificacion']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Descripción: <textarea name="descripcion" required rows="4" style="width:100%;"></textarea></label><br>
    <label>Dirección de la incidencia: <input type="text" name="direccion_incidencia" required></label><br>
    <label>Latitud: <input type="number" step="any" name="latitud"></label><br>
    <label>Longitud: <input type="number" step="any" name="longitud"></label><br>
    <label>Zona:
        <select name="id_zona">
            <option value="">Seleccionar zona</option>
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
    <label>Evidencias (máx. 5 archivos): 
        <input type="file" name="evidencias[]" multiple accept="image/*,video/*,audio/*">
    </label><br><br>

    <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Registrar Incidencia
    </button>
</form>

<script src="<?= BASE_URL ?>/js/registro.js"></script>
<a href="/mesa/dashboard" style="display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none;">
    ← Volver al Dashboard
</a>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>