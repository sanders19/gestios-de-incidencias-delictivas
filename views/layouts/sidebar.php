<!-- views/layouts/sidebar.php -->
<?php
// Simulamos el rol del usuario (deberías obtenerlo de la sesión)
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
?>

<aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <?php if ($rol == 'mesa'): ?>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/mesa/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/mesa/registro">Registrar Incidencia</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/mesa/busqueda">Buscar Incidencias</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/mesa/reportes">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/mesa/perfil">Perfil</a></li>
            <?php elseif ($rol == 'seincri'): ?>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/seincri/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/seincri/atencion">Casos Asignados</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/seincri/busqueda">Buscar Casos</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/seincri/reportes">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/seincri/perfil">Perfil</a></li>
            <?php elseif ($rol == 'jefe'): ?>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/registro">Registrar Incidencia</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/atencion">Asignar Casos</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/asignacion">Panel de Asignación</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/busqueda">Buscar Incidencias</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/reportes">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema-policial/jefe/perfil">Perfil</a></li>
            <?php endif; ?>
        </ul>
    </div>
</aside>