<!-- views/jefe/dashboard.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Dashboard - Jefe</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Incidencias</h5>
                    <p class="card-text">Total: <span id="total-incidencias">0</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Casos Asignados</h5>
                    <p class="card-text">Total: <span id="asignados">0</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Casos Resueltos</h5>
                    <p class="card-text">Total: <span id="resueltos">0</span></p>
                </div>
            </div>
        </div>
    </div>
    <h3>Resumen de Incidencias</h3>
    <div class="row">
        <?php include __DIR__ . '/../components/tarjeta-incidencia.php'; ?>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Incidencia #123</h5>
                    <p class="card-text">Robo en Av. Principal</p>
                    <p class="card-text"><small class="text-muted">Fecha: 2025-10-09</small></p>
                    <a href="/sistema-policial/jefe/busqueda?id=123" class="btn btn-primary">Ver Detalle</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>