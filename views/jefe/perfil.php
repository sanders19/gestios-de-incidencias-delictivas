<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
/* Estilos internos para perfil JEFE (idénticos a SEINCRI) */
.perfil-container {
    background-color: #f8fdf8; /* Verde muy claro de fondo */
    padding: 20px;
    border-left: 5px solid #0a7a0a; /* Verde PNP principal */
    border-radius: 5px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.perfil-container h2 {
    color: #0a7a0a; /* Verde PNP */
    margin-bottom: 20px;
}

.perfil-container p {
    font-size: 1rem;
    margin-bottom: 12px;
    color: #004d00; /* Verde oscuro para textos */
}

.btn-volver {
    display: inline-block;
    margin-top: 15px;
    padding: 8px 15px;
    background-color: #0a7a0a; /* Verde PNP */
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.btn-volver:hover {
    background-color: #046b04; /* Verde más oscuro al pasar mouse */
    text-decoration: none;
}
</style>

<div class="perfil-container">
    <h2>👮‍♂️ Perfil - Jefe</h2>
    <p><strong>Usuario:</strong> <?= htmlspecialchars(Session::get('usuario')['nombre_completo']) ?></p>
    <p><strong>Comisaría:</strong> <?= htmlspecialchars(Session::get('usuario')['comisaria']) ?></p>
    <p><strong>Último acceso:</strong> <?= Session::get('usuario')['ultimo_acceso'] ?: 'Nunca' ?></p>
    <p><strong>Total incidencias en sistema:</strong> <?= $totalIncidencias ?></p>
    <p><strong>Incidencias resueltas:</strong> <?= $resueltas ?></p>
    <a href="<?= BASE_URL ?>/jefe/dashboard" class="btn-volver">← Volver al Dashboard</a>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
