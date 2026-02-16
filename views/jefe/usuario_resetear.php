<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Restablecer contraseña - <?php echo htmlspecialchars($usuario['id_usuario']); ?></h4>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control" minlength="6" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('nueva_contrasena', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmar contraseña</label>
                    <div class="input-group">
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control" minlength="6" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmar_contrasena', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 text-end mt-3">
                    <a href="/jefe/usuarios" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Restablecer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>