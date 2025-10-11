<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Búsqueda Avanzada (Jefe)</h2>
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
    <label>Registrado por (Mesa):
        <select name="registrado_por">
            <option value="">Todos</option>
            <?php foreach ($mesaUsuarios as $u): ?>
                <option value="<?= htmlspecialchars($u['id_usuario']) ?>"><?= htmlspecialchars($u['nombre_completo']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Asignado a (SEINCRI):
        <select name="asignado_a">
            <option value="">Todos</option>
            <?php foreach ($seincriUsuarios as $u): ?>
                <option value="<?= htmlspecialchars($u['id_usuario']) ?>"><?= htmlspecialchars($u['nombre_completo']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Fecha desde: <input type="date" name="fecha_desde"></label><br>
    <label>Fecha hasta: <input type="date" name="fecha_hasta"></label><br>
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($resultados)): ?>
    <h3>Resultados (<?= count($resultados) ?>)</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Dirección</th>
                <th>Registrado por</th>
                <th>Asignado a</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $inc): ?>
                <tr>
                    <td><?= htmlspecialchars($inc['id_incidencia']) ?></td>
                    <td><?= htmlspecialchars($inc['tipo_delito']) ?></td>
                    <td><?= $inc['estado'] ?></td>
                    <td><?= htmlspecialchars($inc['direccion_incidencia']) ?></td>
                    <td><?= htmlspecialchars($inc['registrado_por_nombre']) ?></td>
                    <td><?= htmlspecialchars($inc['asignado_a_nombre']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<script src="/js/busqueda.js"></script>
<a href="/jefe/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>