<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Búsqueda de Casos (SEINCRI)</h2>
<form method="POST">
    <label>Tipo de delito:
        <select name="tipo_delito">
            <option value="">Todos</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= htmlspecialchars($t['tipo_delito']) ?>"><?= htmlspecialchars($t['tipo_delito']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Estado:
        <select name="estado">
            <option value="">Todos</option>
            <option value="Pendiente">Pendiente</option>
            <option value="Recibido">Recibido</option>
            <option value="Investigando">Investigando</option>
            <option value="Resuelto">Resuelto</option>
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
    <label>Fecha desde: <input type="date" name="fecha_desde"></label><br>
    <label>Fecha hasta: <input type="date" name="fecha_hasta"></label><br>
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($resultados)): ?>
    <h3>Resultados (<?= count($resultados) ?>)</h3>
    <ul>
        <?php foreach ($resultados as $inc): ?>
            <li>
                <a href="/seincri/detalle/<?= $inc['id_incidencia'] ?>"><?= htmlspecialchars($inc['id_incidencia']) ?></a> |
                <?= htmlspecialchars($inc['tipo_delito']) ?> |
                <?= $inc['direccion_incidencia'] ?> |
                <?= $inc['estado'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<script src="/js/busqueda.js"></script>
<a href="/seincri/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>