<!-- views/jefe/asignacion.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Asignar Casos</h2>
    <form action="/sistema-policial/jefe/asignacion" method="POST">
        <div class="mb-3">
            <label for="incidencia_id" class="form-label">Seleccionar Incidencia</label>
            <select class="form-control" id="incidencia_id" name="incidencia_id" required>
                <option value="">Seleccione una incidencia</option>
                <!-- Esto debe llenarse dinámicamente desde el controlador -->
                <option value="123">Incidencia #123 - Robo</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="usuario_seincri_id" class="form-label">Asignar a SEINCRI</label>
            <select class="form-control" id="usuario_seincri_id" name="usuario_seincri_id" required>
                <option value="">Seleccione un usuario</option>
                <!-- Esto debe llenarse dinámicamente -->
                <option value="1">Juan Pérez (SEINCRI)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Asignar Caso</button>
    </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>