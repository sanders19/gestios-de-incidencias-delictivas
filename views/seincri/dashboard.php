<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">

    <!-- Título general -->
    <h2 class="mb-4 text-success fw-bold">Dashboard - SEINCRI</h2>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background-color: #28c528ff; color: white;">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Casos asignados</h5>
                    <p class="card-text display-5 fw-bold"><?= count($todos) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background-color: #186b18ff; color: white;">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">En investigación</h5>
                    <p class="card-text display-5 fw-bold"><?= count($investigando) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background-color: #005b2e; color: white;">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Resueltos</h5>
                    <p class="card-text display-5 fw-bold"><?= count($resueltos) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Casos urgentes -->
    <h3 class="fw-bold mb-3" style="color: #0a7a0a;">Casos urgentes</h3>

    <div class="list-group mb-4">
        <?php foreach ($urgentes as $inc): ?>
            <a href="/seincri/detalle/<?= $inc['id_incidencia'] ?>" 
               class="list-group-item list-group-item-action mb-2 d-flex justify-content-between align-items-center"
               style="border-left: 5px solid #56dc35ff; background-color: #dcf3dc;">
                <div>
                    <strong>ID:</strong> <span class="text-danger"><?= htmlspecialchars($inc['id_incidencia']) ?></span> |
                    <strong>Tipo:</strong> <span class="text-dark"><?= htmlspecialchars($inc['tipo_delito']) ?></span> |
                    <strong>Dirección:</strong> <span class="text-dark"><?= $inc['direccion_incidencia'] ?></span>
                </div>
                <span class="badge bg-danger rounded-pill">Urgente</span>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
