<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="bi bi-people"></i> Usuarios</h4>
                    <small class="text-white-50">Gestiona las cuentas por rol o muestra todos</small>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark me-1">Total <strong><?php echo $counts['total'] ?? 0; ?></strong></span>
                        <span class="badge bg-primary me-1">Mesa <strong><?php echo $counts['mesa'] ?? 0; ?></strong></span>
                        <span class="badge bg-warning text-dark me-1">SEINCRI <strong><?php echo $counts['seincri'] ?? 0; ?></strong></span>
                        <span class="badge bg-secondary me-1">Jefe <strong><?php echo $counts['jefe'] ?? 0; ?></strong></span>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap:.5rem; min-width:320px;">
                    <input id="searchUser" type="search" class="form-control form-control-sm" placeholder="Buscar usuario, nombre o ID">
                    <select id="filtroRol" class="form-select form-select-sm ms-2" style="width:150px;">
                        <option value="all" <?php echo ($rol === 'all') ? 'selected' : ''; ?>>Todos</option>
                        <option value="mesa" <?php echo ($rol === 'mesa') ? 'selected' : ''; ?>>Mesa</option>
                        <option value="seincri" <?php echo ($rol === 'seincri') ? 'selected' : ''; ?>>SEINCRI</option>
                        <option value="jefe" <?php echo ($rol === 'jefe') ? 'selected' : ''; ?>>Jefe</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if (!empty($usuarios)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:140px;">ID</th>
                                <th>Usuario</th>
                                <th>Nombre completo</th>
                                <th style="width:110px;">Rol</th>
                                <th>Comisaría</th>
                                <th style="width:90px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usuariosBody">
                            <?php foreach ($usuarios as $u): ?>
                                <tr data-role="<?php echo htmlspecialchars($u['rol']); ?>"
                                    data-search="<?php echo htmlspecialchars(strtolower($u['id_usuario'] . ' ' . $u['nombre_usuario'] . ' ' . $u['nombre_completo'])); ?>">
                                    <td><?php echo htmlspecialchars($u['id_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($u['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
                                    <td>
                                        <?php $r = $u['rol']; if ($r === 'mesa'): ?>
                                            <span class="badge bg-primary">MESA</span>
                                        <?php elseif ($r === 'seincri'): ?>
                                            <span class="badge bg-warning text-dark">SEINCRI</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">JEFE</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($u['comisaria'] ?? ''); ?></td>
                                    <td>
                                        <a href="/jefe/usuario/editar/<?php echo urlencode($u['id_usuario']); ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                        <a href="/jefe/usuario/resetear/<?php echo urlencode($u['id_usuario']); ?>" class="btn btn-sm btn-outline-secondary ms-1" title="Restablecer contraseña"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a href="/jefe/usuario/eliminar/<?php echo urlencode($u['id_usuario']); ?>" class="btn btn-sm btn-outline-danger ms-1" title="Eliminar"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="small text-muted mt-2">
                    Mostrando <strong id="countVisible"><?php echo count($usuarios); ?></strong> de <strong><?php echo count($usuarios); ?></strong> registros
                </div>
            <?php else: ?>
                <p class="text-muted">No hay usuarios para mostrar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Filtrado combinado por rol y búsqueda (cliente-side, sin recarga)
    function filterRows() {
        var rol = document.getElementById('filtroRol').value;
        var term = document.getElementById('searchUser').value.toLowerCase().trim();
        var rows = document.querySelectorAll('#usuariosBody tr');
        var visibleCount = 0;

        rows.forEach(function(r) {
            var rowRole = r.getAttribute('data-role') || '';
            var searchData = r.getAttribute('data-search') || '';
            
            var matchesRole = (rol === 'all') || (rowRole === rol);
            var matchesTerm = term === '' || searchData.indexOf(term) !== -1;
            
            if (matchesRole && matchesTerm) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        // Actualizar contador
        var countElement = document.getElementById('countVisible');
        if (countElement) {
            countElement.textContent = visibleCount;
        }
    }

    var filtro = document.getElementById('filtroRol');
    var busqueda = document.getElementById('searchUser');

    // Inicializar desde querystring si existe
    (function initFilters() {
        var params = new URLSearchParams(window.location.search);
        var rolParam = params.get('rol');
        var qParam = params.get('q');
        
        if (rolParam && filtro) {
            filtro.value = rolParam;
        }
        if (qParam && busqueda) {
            busqueda.value = qParam;
        }
        
        filterRows();
    })();

    // Event listener para cambio de rol
    if (filtro) {
        filtro.addEventListener('change', function() {
            filterRows();
            
            // Actualizar URL sin recargar (útil para compartir)
            var params = new URLSearchParams(window.location.search);
            if (this.value === 'all') {
                params.delete('rol');
            } else {
                params.set('rol', this.value);
            }
            
            var q = busqueda.value.trim();
            if (q) {
                params.set('q', q);
            } else {
                params.delete('q');
            }
            
            var newUrl = window.location.pathname + (params.toString() ? ('?' + params.toString()) : '');
            history.replaceState(null, '', newUrl);
        });
    }

    // Event listener para búsqueda
    if (busqueda) {
        busqueda.addEventListener('input', function() {
            filterRows();
        });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>