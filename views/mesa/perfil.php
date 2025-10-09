<!-- views/mesa/perfil.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-4">
    <h2>Mi Perfil</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Información del Usuario</h5>
            <!-- Datos estáticos, deben venir del controlador -->
            <p><strong>Nombre:</strong> <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario'; ?></p>
            <p><strong>Correo:</strong> <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'correo@ejemplo.com'; ?></p>
            <p><strong>Rol:</strong> Mesa de Partes</p>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPerfil">Editar Perfil</a>
        </div>
    </div>
    <!-- Modal para editar perfil -->
    <div class="modal fade" id="editarPerfil" tabindex="-1" aria-labelledby="editarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPerfilLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/sistema-policial/mesa/perfil" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>