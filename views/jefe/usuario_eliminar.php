<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Eliminar Usuario - <?php echo htmlspecialchars($usuario['id_usuario']); ?></h4>
        </div>
        <div class="card-body">
            <p>¿Estás seguro que deseas eliminar al usuario <strong><?php echo htmlspecialchars($usuario['nombre_completo']); ?></strong> (<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>)? Esta acción no se puede deshacer.</p>

            <form method="POST">
                <a href="/jefe/usuarios" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-danger">Eliminar usuario</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>