<?php
// Obtener el rol desde la sesión del usuario
$rol = $_SESSION['usuario']['rol'] ?? 'mesa';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/sidebar.css">


<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <?php if ($rol === 'mesa'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/mesa/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/mesa/registro">
                        <i class="bi bi-file-earmark-plus"></i> Registrar Incidencia
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/mesa/mis-registros">
                        <i class="bi bi-search"></i>  Incidencias Registradas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/mesa/reportes">
                        <i class="bi bi-file-text"></i> Reportes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/mesa/perfil">
                        <i class="bi bi-person-circle"></i> Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            <?php elseif ($rol === 'seincri'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/seincri/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/seincri/atencion">
                        <i class="bi bi-clipboard-check"></i> Atender Incidencias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/seincri/busqueda">
                        <i class="bi bi-search"></i> Buscar Casos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/seincri/reportes">
                        <i class="bi bi-file-text"></i> Reportes
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/seincri/perfil">
                        <i class="bi bi-person-circle"></i> Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            <?php elseif ($rol === 'jefe'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/asignacion">
                        <i class="bi bi-people"></i> Asignaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/busqueda">
                        <i class="bi bi-search"></i> Buscar Todas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/reportes">
                        <i class="bi bi-graph-up"></i> Reportes Globales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/crear_usuario">
                        <i class="bi bi-person-plus"></i> Crear Usuario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/usuarios">
                        <i class="bi bi-people"></i> Ver Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/jefe/perfil">
                        <i class="bi bi-person-circle"></i> Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<script src="<?= BASE_URL ?>/js/sidebar.js"></script>

