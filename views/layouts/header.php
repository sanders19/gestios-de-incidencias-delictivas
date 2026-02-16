<?php
/**
 * Header Principal del Sistema
 * Sistema Policial Huancavelica
 */

// Obtener mensajes flash si existe la clase Session
$flash = class_exists('Session') ? Session::getFlash() : null;

// Obtener información del usuario (si está disponible)
$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'mesa';

// Convertir rol a nombre legible
$rolNombre = 'Usuario';
switch(strtolower($rolUsuario)) {
    case 'mesa':
        $rolNombre = 'Mesa de Partes';
        break;
    case 'seincri':
        $rolNombre = 'SEINCRI';
        break;
    case 'jefe':
        $rolNombre = 'Jefe';
        break;
}

// Determinar URL del dashboard según rol
$dashboardUrl = BASE_URL . '/login'; // Por defecto
if (isset($_SESSION['usuario'])) {
    $rol = strtolower($_SESSION['usuario']['rol']);
    switch($rol) {
        case 'mesa':
            $dashboardUrl = BASE_URL . '/mesa/dashboard';
            break;
        case 'seincri':
            $dashboardUrl = BASE_URL . '/seincri/dashboard';
            break;
        case 'jefe':
            $dashboardUrl = BASE_URL . '/jefe/dashboard';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistema de Gestión Policial - Comisaría Huancavelica">
    <meta name="author" content="PNP Huancavelica">
    
    <!-- Título -->
    <title><?= APP_NAME ?? 'Sistema Policial Huancavelica' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/img/favicon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/sidebar.css">
</head>
<body>

    <!-- Mensajes Flash -->
    <?php if ($flash): ?>
        <div class="alert-flash alert alert-<?= $flash['type'] ?> alert-dismissible fade show m-0" role="alert">
            <div class="container-fluid d-flex align-items-center justify-content-center">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : ($flash['type'] === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill') ?> me-2"></i>
                <span><?= htmlspecialchars($flash['message']) ?></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container-fluid">
            <!-- Logo y Nombre con redirección dinámica al dashboard -->
            <a class="navbar-brand d-flex align-items-center" href="<?= $dashboardUrl ?>">
                <img src="<?= BASE_URL ?>/img/logo.png" alt="Logo PNP" class="navbar-logo me-2">
                <div class="navbar-brand-text">
                    <span class="brand-title">Sistema Policial</span>
                    <small class="brand-subtitle d-none d-md-block">Comisaría Huancavelica</small>
                </div>
            </a>

            <!-- Botón toggle para móviles -->
            <button class="navbar-toggler border-0" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú derecho -->
            <div class="navbar-right ms-auto d-none d-lg-flex align-items-center">
                <!-- Fecha y hora -->
                <div class="navbar-datetime me-3">
                    <i class="bi bi-calendar3"></i>
                    <span id="currentDate"><?= date('d/m/Y') ?></span>
                    <i class="bi bi-clock ms-2"></i>
                    <span id="currentTime"><?= date('H:i') ?></span>
                </div>

                <!-- Usuario con rol (sin icono) -->
                <div class="navbar-user">
                    <div class="d-flex flex-column align-items-end">
                        <span class="fw-semibold"><?= htmlspecialchars($nombreUsuario) ?></span>
                        <small class="text-white-50" style="font-size: 0.75rem; line-height: 1;">
                            <?= htmlspecialchars($rolNombre) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include __DIR__ . '/sidebar.php'; ?>
            
            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
