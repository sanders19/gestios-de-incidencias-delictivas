<!-- views/components/modal-evidencia.php -->
<div class="modal fade" id="modalEvidencia" tabindex="-1" aria-labelledby="modalEvidenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEvidenciaLabel">Subir Evidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/sistema-policial/subir-evidencia" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Evidencia</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="foto">Foto</option>
                            <option value="video">Video</option>
                            <option value="documento">Documento</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" required accept="image/*,video/*,.pdf">
                    </div>
                    <input type="hidden" name="incidencia_id" value="{ID de la incidencia}">
                    <button type="submit" class="btn btn-primary">Subir Evidencia</button>
                </form>
            </div>
        </div>
    </div>
</div>