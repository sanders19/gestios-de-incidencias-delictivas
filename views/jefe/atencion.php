<!-- views/jefe/atencion.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Gestionar Casos</h2>
    <div class="row">
        <?php include __DIR__ . '/../components/tarjeta-incidencia.php'; ?>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Incidencia #123</h5>
                    <p class="card-text">Robo en Av. Principal</p>
                    <p class="card-text"><small class="text-muted">Asignado a: Juan Pérez</small></p>
                    <a href="/sistema-policial/jefe/asignacion?id=123" class="btn btn-primary">Reasignar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>