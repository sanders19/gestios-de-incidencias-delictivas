<!-- views/mesa/reportes.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Reportes - Mesa de Partes</h2>
    <form action="/sistema-policial/mesa/reportes" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select class="form-control" name="tipo_reporte">
                    <option value="">Seleccionar tipo</option>
                    <option value="diario">Reporte Diario</option>
                    <option value="mensual">Reporte Mensual</option>
                    <option value="anual">Reporte Anual</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="fecha_inicio">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="fecha_fin">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Generar</button>
            </div>
        </div>
    </form>
    <h3>Reportes Generados</h3>
    <ul class="list-group">
        <!-- Ejemplo estático -->
        <li class="list-group-item">
            <a href="/sistema-policial/public/uploads/reportes/reporte_2025-10-09.pdf" target="_blank">Reporte Diario 2025-10-09</a>
        </li>
    </ul>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>