<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Dashboard - SEINCRI</h2>
<p><strong>Casos asignados:</strong> <?= count($todos) ?></p>
<p><strong>En investigación:</strong> <?= count($investigando) ?></p>
<p><strong>Resueltos:</strong> <?= count($resueltos) ?></p>

<h3>Casos urgentes</h3>
<ul>
    <?php foreach ($urgentes as $inc): ?>
        <li>
            <a href="/seincri/detalle/<?= $inc['id_incidencia'] ?>"><?= htmlspecialchars($inc['id_incidencia']) ?></a> -
            <?= htmlspecialchars($inc['tipo_delito']) ?> -
            <?= $inc['direccion_incidencia'] ?>
        </li>
    <?php endforeach; ?>
</ul>

<nav>
    <a href="/seincri/atencion">Atención de Casos</a> |
    <a href="/seincri/busqueda">Buscar</a> |
    <a href="/seincri/reportes">Reportes</a> |
    <a href="/seincri/perfil">Perfil</a> |
    <a href="/logout">Salir</a>
</nav>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>