<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
/* Estilos internos para perfil */
.perfil-container {
    background-color: #fff;
    padding: 20px;
    border-left: 5px solid #006400; /* Verde PNP */
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.perfil-container h2 {
    color: #004d00;
    margin-bottom: 20px;
}

.perfil-container p {
    font-size: 1rem;
    margin-bottom: 10px;
}

.btn-volver {
    display: inline-block;
    margin-top: 15px;
    padding: 8px 15px;
    background-color: #006400;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.btn-volver:hover {
    background-color: #004d00;
    text-decoration: none;
}
</style>

<div class="perfil-container">
    <h2>👤 Perfil - Mesa de Partes</h2>
    <p><strong>Usuario:</strong> <?= htmlspecialchars(Session::get('usuario')['nombre_completo']) ?></p>
    <p><strong>Comisaría:</strong> <?= htmlspecialchars(Session::get('usuario')['comisaria']) ?></p>
    <p><strong>Último acceso:</strong> <?= Session::get('usuario')['ultimo_acceso'] ?: 'Nunca' ?></p>
    <p><strong>Incidencias registradas:</strong> <?= $totalRegistradas ?></p>
    
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
