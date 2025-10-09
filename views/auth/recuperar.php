<!-- views/auth/recuperar.php -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Recuperar Contraseña</h3>
                </div>
                <div class="card-body">
                    <form action="/sistema-policial/recuperar" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Enlace de Recuperación</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/sistema-policial/login">Volver al inicio de sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>