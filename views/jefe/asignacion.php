<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Historial de Asignaciones</h2>
<?php if (empty($asignaciones)): ?>
    <p>No hay asignaciones registradas.</p>
<?php else: ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID Incidencia</th>
                <th>Tipo</th>
                <th>Asignado a</th>
                <th>Asignado por</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($asignaciones as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id_incidencia']) ?></td>
                    <td><?= htmlspecialchars($a['tipo_delito']) ?></td>
                    <td><?= htmlspecialchars($a['asignado_a_nombre']) ?></td>
                    <td><?= htmlspecialchars($a['asignado_por_nombre']) ?></td>
                    <td><?= $a['asignado_en'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<a href="/jefe/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>