<!-- views/mesa/registro.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Registrar Incidencia</h2>
    <form action="/sistema-policial/mesa/registro" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" name="ubicacion">
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
        </div>
        <div class="mb-3">
            <label for="evidencia" class="form-label">Subir Evidencia (Foto/Video)</label>
            <input type="file" class="form-control" id="evidencia" name="evidencia[]" multiple accept="image/*,video/*">
        </div>
        <button type="submit" class="btn btn-primary">Registrar Incidencia</button>
    </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>