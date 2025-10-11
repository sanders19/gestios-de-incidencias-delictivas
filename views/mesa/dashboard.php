<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Dashboard - Mesa de Partes</h2>
<p><strong>Incidencias hoy:</strong> <?= count($incidenciasHoy) ?></p>
<p><strong>Últimos registros:</strong></p>
<ul>
    <?php foreach (array_slice($ultimos, 0, 5) as $inc): ?>
        <li><?= htmlspecialchars($inc['id_incidencia']) ?> - <?= htmlspecialchars($inc['tipo_delito']) ?> - <?= $inc['estado'] ?></li>
    <?php endforeach; ?>
</ul>
<nav>
    <a href="/mesa/registro">Registrar Incidencia</a> |
    <a href="/mesa/busqueda">Buscar</a> |
    <a href="/mesa/reportes">Reportes</a> |
    <a href="/mesa/perfil">Perfil</a> |
    <a href="/logout">Salir</a>
</nav>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>