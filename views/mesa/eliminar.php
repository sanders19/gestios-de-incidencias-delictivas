<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-danger">
                <div class="card-header bg-danger text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-danger">
                        <strong>¡ADVERTENCIA!</strong> Esta acción no se puede deshacer.
                    </div>

                    <h5 class="mb-3">¿Estás seguro de eliminar esta incidencia?</h5>
                    
                    <div class="bg-light p-3 rounded mb-4">
                        <p class="mb-2"><strong>ID:</strong> <span class="badge bg-dark">#<?= htmlspecialchars($incidencia['id_incidencia']) ?></span></p>
                        <p class="mb-2"><strong>Tipo:</strong> <?= htmlspecialchars($incidencia['tipo_delito']) ?></p>
                        <p class="mb-2"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($incidencia['fecha_registro'])) ?></p>
                        <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-warning text-dark"><?= htmlspecialchars($incidencia['estado']) ?></span></p>
                    </div>

                    <form method="POST" id="formEliminar">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-trash"></i> Sí, Eliminar Incidencia
                            </button>
                            <a href="<?= BASE_URL ?>/mesa/dashboard/<?= $incidencia['id_incidencia'] ?>" 
                               class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> No, Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formEliminar').addEventListener('submit', function(e) {
    if (!confirm('¿CONFIRMAS QUE DESEAS ELIMINAR ESTA INCIDENCIA PERMANENTEMENTE?')) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
