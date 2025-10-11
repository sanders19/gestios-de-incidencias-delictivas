<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Generar Reporte Global</h2>
<form method="POST">
    <label>Periodo:
        <select name="periodo" required>
            <option value="7 días">Últimos 7 días</option>
            <option value="Mes" selected>Mes actual</option>
            <option value="Trimestre">Trimestre</option>
            <option value="Año">Año</option>
        </select>
    </label><br>
    <label>Tipo de delito:
        <select name="tipo_delito">
            <option value="">Todos</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= htmlspecialchars($t['tipo_delito']) ?>"><?= htmlspecialchars($t['tipo_delito']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Zona:
        <select name="id_zona">
            <option value="">Todas</option>
            <?php foreach ($zonas as $z): ?>
                <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Registrado por (Mesa):
        <select name="id_registrado_por">
            <option value="">Todos</option>
            <?php foreach ($mesaUsuarios as $u): ?>
                <option value="<?= htmlspecialchars($u['id_usuario']) ?>"><?= htmlspecialchars($u['nombre_completo']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Asignado a (SEINCRI):
        <select name="id_asignado_a">
            <option value="">Todos</option>
            <?php foreach ($seincriUsuarios as $u): ?>
                <option value="<?= htmlspecialchars($u['id_usuario']) ?>"><?= htmlspecialchars($u['nombre_completo']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Generar y Exportar PDF</button>
</form>
<script src="/js/reportes.js"></script>
<a href="/jefe/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>