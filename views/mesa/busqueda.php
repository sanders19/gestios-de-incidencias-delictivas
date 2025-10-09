<!-- views/mesa/busqueda.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Buscar Incidencias</h2>
    <form action="/sistema-policial/mesa/busqueda" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="query" placeholder="Título o ID de incidencia">
            </div>
            <div class="col-md-3">
                <select class="form-control" name="estado">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_progreso">En Progreso</option>
                    <option value="resuelta">Resuelta</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="fecha">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </form>
    <h3>Resultados</h3>
    <div class="row">
        <!-- Ejemplo de resultados -->
        <?php include __DIR__ . '/../components/tarjeta-incidencia.php'; ?>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Incidencia #123</h5>
                    <p class="card-text">Robo en Av. Principal</p>
                    <p class="card-text"><small class="text-muted">Fecha: 2025-10-09</small></p>
                    <a href="/sistema-policial/mesa/busqueda?id=123" class="btn btn-primary">Ver Detalle</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>