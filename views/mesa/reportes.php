<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Reportes (Mesa)</h2>
<form method="POST">
    <label>Periodo:
        <select name="periodo">
            <option value="7 días">Últimos 7 días</option>
            <option value="Mes" selected>Este mes</option>
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
    <button type="submit">Generar Reporte</button>
</form>

<?php if (!empty($estadisticas)): ?>
    <h3>Estadísticas</h3>
    <p>Total registradas: <?= $estadisticas['total'] ?></p>
    <h4>Por tipo de delito:</h4>
    <ul>
        <?php foreach ($estadisticas['por_tipo'] as $tipo => $cant): ?>
            <li><?= htmlspecialchars($tipo) ?>: <?= $cant ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<script src="/js/reportes.js"></script>
<a href="/mesa/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>