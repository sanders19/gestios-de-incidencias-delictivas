<!-- views/seincri/atencion.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Casos Asignados</h2>
    <div class="row">
        <!-- Ejemplo de tarjeta de incidencia -->
        <?php include __DIR__ . '/../components/tarjeta-incidencia.php'; ?>
        <!-- Iterar sobre incidencias asignadas (esto requiere lógica en el controlador) -->
        <!-- Ejemplo estático -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Incidencia #123</h5>
                    <p class="card-text">Robo en Av. Principal</p>
                    <p class="card-text"><small class="text-muted">Asignada: 2025-10-09</small></p>
                    <a href="/sistema-policial/seincri/detalle?id=123" class="btn btn-primary">Ver Detalle</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>