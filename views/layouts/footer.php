            </main>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="footer-text mb-0">
                        &copy; <?= date('Y') ?> <strong>Sistema Policial Huancavelica</strong>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="footer-links mb-0">
                        <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modalAyuda">
                            <i class="bi bi-question-circle"></i> Ayuda
                        </a>
                        <span class="separator">|</span>
                        <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modalSoporte">
                            <i class="bi bi-headset"></i> Soporte
                        </a>
                        <span class="separator">|</span>
                        <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modalAcerca">
                            <i class="bi bi-info-circle"></i> Acerca de
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Ayuda -->
    <div class="modal fade" id="modalAyuda" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-question-circle-fill"></i> Centro de Ayuda
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center mb-3">
                        El personal ha sido capacitado para el uso del sistema.<br>
                        Para consultas sobre funcionalidades, contacta a tu supervisor.
                    </p>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>/docs/manual_sistema.pdf" 
                           target="_blank" 
                           class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf"></i> Descargar Manual de Usuario (PDF)
                        </a>
                    </div>
                    <p class="text-muted text-center mt-3 mb-0 small">
                        <i class="bi bi-info-circle"></i> Para problemas técnicos, ir a <strong>Soporte</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Soporte -->
    <div class="modal fade" id="modalSoporte" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-headset"></i> Soporte Técnico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-badge text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-center fw-bold mb-3">Desarrolladores del Sistema</h6>
                    
                    <div class="card border-success mb-2">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-person-circle text-success"></i>
                                    <strong>José Adolfo Mayhua Palomino</strong>
                                </div>
                                <a href="https://wa.me/51925758041" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-whatsapp"></i> 925 758 041
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-success mb-3">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-person-circle text-success"></i>
                                    <strong>Tapara Esplana Herbert Anderson</strong>
                                </div>
                                <a href="https://wa.me/51949771302" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-whatsapp"></i> 949 771 302
                                </a>
                            </div>
                        </div>
                    </div>

                    <p class="text-center text-muted mb-0 small">
                        <i class="bi bi-clock"></i> Contáctanos para problemas técnicos del sistema
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Acerca de -->
    <div class="modal fade" id="modalAcerca" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle-fill"></i> Acerca del Sistema
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?= BASE_URL ?>/img/logo.png" alt="Logo PNP" style="width: 100px;" class="mb-3">
                    <h5 class="fw-bold text-primary">Sistema Policial Huancavelica</h5>
                    <p class="text-muted mb-1">Versión 1.0.0</p>
                    <p class="text-muted mb-3">Año <?= date('Y') ?></p>
                    
                    <hr>
                    
                    <p class="mb-2">
                        <i class="bi bi-building text-primary"></i><br>
                        <strong>Comisaría de Huancavelica</strong><br>
                        Policía Nacional del Perú
                    </p>
                    
                    <hr>
                    
                    <p class="mb-2">
                        <i class="bi bi-code-slash text-success"></i><br>
                        <strong>Desarrollado por:</strong><br>
                        José Adolfo Mayhua Palomino<br>
                        Tapara Esplana Herbert Anderson
                    </p>
                    
                    <hr>
                    
                    <p class="text-muted small mb-1">
                        <i class="bi bi-shield-check"></i>
                        Sistema de gestión de incidencias delictivas
                    </p>
                    <p class="text-muted small mb-0">
                        © <?= date('Y') ?> Todos los derechos reservados
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="<?= BASE_URL ?>/js/sidebar.js"></script>
    <script src="<?= BASE_URL ?>/js/layout.js"></script>

</body>
</html>
