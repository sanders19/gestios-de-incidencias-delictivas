<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .password-wrapper {
        position: relative;
        display: inline-block;
        width: auto;
    }
    .password-wrapper input {
        padding-right: 35px;
    }
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        user-select: none;
        color: #666;
    }
    .toggle-password:hover {
        color: #000;
    }
</style>

<h2>Cambiar Contraseña (Primer Inicio)</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nueva contraseña: 
        <span class="password-wrapper">
            <input type="password" name="nueva_contrasena" id="nueva_contrasena" minlength="6" required>
            <span class="toggle-password" onclick="togglePassword('nueva_contrasena', this)">
                👁️
            </span>
        </span>
    </label><br><br>
    
    <label>Confirmar: 
        <span class="password-wrapper">
            <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" minlength="6" required>
            <span class="toggle-password" onclick="togglePassword('confirmar_contrasena', this)">
                👁️
            </span>
        </span>
    </label><br><br>
    
    <button type="submit">Actualizar Contraseña</button>
</form>

<br>


<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈'; // Ojo cerrado
    } else {
        input.type = 'password';
        icon.textContent = '👁️'; // Ojo abierto
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
