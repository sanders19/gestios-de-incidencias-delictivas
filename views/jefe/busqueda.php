<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-search"></i> Búsqueda Avanzada (Jefe)</h4>
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3 p-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de delito</label>
                    <select name="tipo_delito" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= htmlspecialchars($t['tipo_delito']) ?>">
                                <?= htmlspecialchars($t['tipo_delito']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Recibido">Recibido</option>
                        <option value="Investigando">Investigando</option>
                        <option value="Resuelto">Resuelto</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Zona</label>
                    <select name="id_zona" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($zonas as $z): ?>
                            <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Registrado por (Mesa)</label>
                    <select name="registrado_por" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($mesaUsuarios as $u): ?>
                            <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                                <?= htmlspecialchars($u['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Asignado a (SEINCRI)</label>
                    <select name="asignado_a" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($seincriUsuarios as $u): ?>
                            <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                                <?= htmlspecialchars($u['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha desde</label>
                    <input type="date" name="fecha_desde" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control">
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($resultados)): ?>
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Resultados (<?= count($resultados) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Dirección</th>
                                <th>Registrado por</th>
                                <th>Asignado a</th>
                                <th>Acciones</th>
...
<td>
    <a href="/jefe/asignacion?id_incidencia=<?= $inc['id_incidencia'] ?>" 
       class="btn btn-outline-success btn-sm">
        <i class="bi bi-person-plus"></i> Asignar
    </a>
</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $inc): ?>
                                <tr>
                                    <td><?= htmlspecialchars($inc['id_incidencia']) ?></td>
                                    <td><?= htmlspecialchars($inc['tipo_delito']) ?></td>
                                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($inc['estado']) ?></span></td>
                                        <td><?= htmlspecialchars($inc['direccion_incidencia']) ?></td>
                                        <td><?= htmlspecialchars($inc['registrado_por_nombre']) ?></td>
                                        <td><?= htmlspecialchars($inc['asignado_a'] ?? 'No asignado') ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
</div>

<script src="/js/busqueda.js"></script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
