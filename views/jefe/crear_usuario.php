<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</h4>
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3 p-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">ID Usuario </label>
                    <!-- Mostrar ID generado automáticamente y enviar en hidden -->
                    <input type="text" id="display_id_usuario" class="form-control" readonly>
                    <input type="hidden" name="id_usuario" id="id_usuario">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nombre de usuario (único)</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contraseña inicial</label>
                    <div class="input-group">
                        <input type="password" name="contrasena" id="contrasena_input" class="form-control" minlength="6" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1" aria-label="Mostrar contraseña">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="rol" id="rol_select" class="form-select" required>
                        <option value="mesa">Mesa de Partes</option>
                        <option value="seincri">SEINCRI</option>
                        <option value="jefe">Jefe</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre completo</label>
                    <input type="text" name="nombre_completo" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Comisaría</label>
                    <input type="text" name="comisaria" class="form-control" value="Huancavelica Centro">
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    (function() {
        // Precalcular los siguientes IDs pasados desde el controlador
        var siguientes = <?php echo json_encode($siguientes ?? []); ?>;

        var rolSelect = document.getElementById('rol_select');
        var displayId = document.getElementById('display_id_usuario');
        var hiddenId = document.getElementById('id_usuario');

        function actualizarId() {
            var rol = rolSelect.value;
            var id = siguientes[rol] || '';
            displayId.value = id;
            hiddenId.value = id;
        }

        // Inicializar con el primer valor
        if (rolSelect) {
            rolSelect.addEventListener('change', actualizarId);
            actualizarId();
        }

        // Toggle ver/ocultar contraseña
        var passwordInput = document.getElementById('contrasena_input');
        var toggleBtn = document.getElementById('togglePassword');
        var toggleIcon = document.getElementById('togglePasswordIcon');
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            });
        }
    })();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
        </div>
    </div>


</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
