<!-- views/components/notificacion.php -->
<?php if (isset($_SESSION['notificacion'])): ?>
    <div class="alert alert-<?php echo $_SESSION['notificacion']['tipo']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['notificacion']['mensaje']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['notificacion']); ?>
<?php endif; ?>