<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Atención de Casos Asignados</h2>
<?php if (empty($incidencias)): ?>
    <p>No tienes casos asignados.</p>
<?php else: ?>
    <ul>
        <?php foreach ($incidencias as $inc): ?>
            <li>
                <strong><?= htmlspecialchars($inc['id_incidencia']) ?></strong> -
                <?= htmlspecialchars($inc['tipo_delito']) ?> -
                <?= $inc['direccion_incidencia'] ?> -
                Estado: <?= $inc['estado'] ?> -
                <a href="/seincri/detalle/<?= $inc['id_incidencia'] ?>">Ver/Actualizar</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<a href="/seincri/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>