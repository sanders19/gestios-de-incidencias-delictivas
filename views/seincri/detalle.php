<!-- views/seincri/detalle.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Detalles del Caso #123</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Robo en Av. Principal</h5>
            <p><strong>Descripción:</strong> Robo reportado en la avenida principal a las 14:00.</p>
            <p><strong>Ubicación:</strong> Av. Principal, Huancavelica</p>
            <p><strong>Fecha:</strong> 2025-10-09 14:00</p>
            <p><strong>Estado:</strong> En Progreso</p>
            <h6>Evidencias</h6>
            <div class="row">
                <div class="col-md-4">
                    <img src="/sistema-policial/public/uploads/evidencias/evidencia_123.jpg" class="img-fluid" alt="Evidencia">
                </div>
            </div>
            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalEvidencia">Subir Evidencia</a>
            <a href="/sistema-policial/seincri/atencion" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../components/modal-evidencia.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>