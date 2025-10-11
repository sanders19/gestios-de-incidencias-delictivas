<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Perfil - Mesa de Partes</h2>
<p><strong>Usuario:</strong> <?= htmlspecialchars(Session::get('usuario')['nombre_completo']) ?></p>
<p><strong>Comisaría:</strong> <?= htmlspecialchars(Session::get('usuario')['comisaria']) ?></p>
<p><strong>Último acceso:</strong> <?= Session::get('usuario')['ultimo_acceso'] ?: 'Nunca' ?></p>
<p><strong>Incidencias registradas:</strong> <?= $totalRegistradas ?></p>
<a href="/mesa/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>