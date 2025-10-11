<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Dashboard - Jefe</h2>
<p><strong>Total incidencias:</strong> <?= count($todas) ?></p>
<p><strong>Pendientes:</strong> <?= count($pendientes) ?></p>
<p><strong>Resueltas:</strong> <?= count($resueltas) ?></p>

<h3>Estadísticas por Agente SEINCRI</h3>
<?php if (!empty($agentes)): ?>
    <ul>
        <?php foreach ($agentes as $id => $stats): ?>
            <li>
                <?= htmlspecialchars($id) ?>: 
                Total <?= $stats['total'] ?>, 
                Resueltos <?= $stats['resueltos'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Sin asignaciones registradas.</p>
<?php endif; ?>

<nav>
    <a href="/jefe/atencion">Asignar Casos</a> |
    <a href="/jefe/asignacion">Asignaciones</a> |
    <a href="/jefe/busqueda">Buscar Todas</a> |
    <a href="/jefe/reportes">Reportes Globales</a> |
    <a href="/jefe/crear-usuario">Crear Usuario</a> |
    <a href="/jefe/perfil">Perfil</a> |
    <a href="/logout">Salir</a>
</nav>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>