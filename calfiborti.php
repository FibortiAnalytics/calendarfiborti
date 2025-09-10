<?php
/**
 * Template Name: Calendario Fiborti
 * Description: Página especial para el sistema de calendario de Fiborti
 */

// Enqueue el archivo JavaScript específico para esta página
function enqueue_calfiborti_scripts() {
    // Verificar que el archivo existe antes de cargarlo
    $js_file_path = get_template_directory() . '/js/calfiborti.js';
    if (file_exists($js_file_path)) {
        wp_enqueue_script(
            'calfiborti-js',
            get_template_directory_uri() . '/js/calfiborti.js',
            array('jquery', 'bootstrap-js'), // Dependencias correctas
            filemtime($js_file_path), // Versión basada en fecha de modificación
            true
        );
    } else {
        // Log error si el archivo no existe
        error_log('Error: Archivo calfiborti.js no encontrado en: ' . $js_file_path);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_calfiborti_scripts');

get_header(); ?>

<div class="container content-area">
    <div class="row">
        <main class="col-12">
            <?php while (have_posts()) : the_post(); ?>
                <article class="card mb-4">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php the_title(); ?>
                        </h1>
                        
                        <?php if (get_the_content()) : ?>
                            <div class="card-text mb-4">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Sección del Formulario de Disponibilidad -->
                        <div class="fiborti-availability-section">
                            <div class="row">
                                <div class="col-lg-5 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Detalles de la Reunión</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="disponibilidadForm">
                                                <!-- Modalidad -->
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">¿La reunión será Presencial o Remota? *</label>
                                                    <div class="mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="modalidad" id="presencial" value="presencial" required>
                                                            <label class="form-check-label" for="presencial">
                                                                <i class="fas fa-users me-1"></i> Presencial
                                                                <small class="d-block text-muted">Martes a viernes, 10:00 AM - 4:30 PM</small>
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="modalidad" id="remota" value="remota" required>
                                                            <label class="form-check-label" for="remota">
                                                                <i class="fas fa-video me-1"></i> Remota
                                                                <small class="d-block text-muted">Lunes a viernes, 9:00 AM - 5:30 PM</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Duración de la reunión (solo para remotas) -->
                                                <div class="mb-4" id="duracionSection" style="display: none;">
                                                    <label class="form-label fw-bold">Duración de la reunión remota: *</label>
                                                    <div class="mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="duracion" id="duracion30" value="30">
                                                            <label class="form-check-label" for="duracion30">
                                                                <i class="fas fa-clock me-1"></i> 30 minutos
                                                                <small class="d-block text-muted">Reunión rápida o seguimiento</small>
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="duracion" id="duracion45" value="45">
                                                            <label class="form-check-label" for="duracion45">
                                                                <i class="fas fa-clock me-1"></i> 45 minutos
                                                                <small class="d-block text-muted">Reunión estándar</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Las reuniones presenciales son siempre de 60 minutos mínimo</small>
                                                </div>

                                                <!-- Selección de Colaboradores/Calendarios -->
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Selecciona los colaboradores/calendarios: *</label>
                                                    <div class="mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="danny" value="Danny">
                                                            <label class="form-check-label" for="danny">
                                                                <i class="fas fa-user me-1"></i> Danny Cen
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="mauricio" value="Mauricio">
                                                            <label class="form-check-label" for="mauricio">
                                                                <i class="fas fa-user me-1"></i> Mauricio León
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="fernando" value="Fernando">
                                                            <label class="form-check-label" for="fernando">
                                                                <i class="fas fa-user me-1"></i> Fernando Fiborti
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="emilio" value="Emilio">
                                                            <label class="form-check-label" for="emilio">
                                                                <i class="fas fa-user me-1"></i> Emilio Fiborti
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="fiborti-team" value="Fiborti Team">
                                                            <label class="form-check-label" for="fiborti-team">
                                                                <i class="fas fa-users me-1"></i> Calendario Fiborti (Equipo)
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="maximiliano" value="Maximiliano">
                                                            <label class="form-check-label" for="maximiliano">
                                                                <i class="fas fa-user me-1"></i> Maximiliano
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="anthea" value="Anthea">
                                                            <label class="form-check-label" for="anthea">
                                                                <i class="fas fa-user me-1"></i> Anthea
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="colaboradores[]" id="mayte" value="Mayte">
                                                            <label class="form-check-label" for="mayte">
                                                                <i class="fas fa-user me-1"></i> Mayte
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Selecciona uno o múltiples colaboradores para consultar disponibilidad</small>
                                                    <div class="alert alert-info mt-2">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        <strong>Importante:</strong> Solo se mostrarán horarios donde <strong>TODOS</strong> los colaboradores seleccionados estén disponibles. El evento se agendará automáticamente en todos los calendarios.
                                                    </div>
                                                </div>

                                                <!-- Fecha -->
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Fecha de la reunión *</label>
                                                    <input type="date" class="form-control" id="fechaReunion" min="<?php echo date('Y-m-d'); ?>" required>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        El sistema validará automáticamente los días permitidos según la modalidad
                                                    </small>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                                    <i class="fas fa-search me-2"></i>Consultar Disponibilidad
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-7">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Horarios Disponibles</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="disponibilidadResultado">
                                                <div class="text-center text-muted py-5">
                                                    <i class="fas fa-calendar-check fa-4x mb-3"></i>
                                                    <h5>Consulta la disponibilidad</h5>
                                                    <p>Selecciona modalidad, colaboradores y fecha para ver horarios disponibles</p>
                                                    <div class="mt-3">
                                                        <small class="text-info">
                                                            <i class="fas fa-shield-alt me-1"></i>
                                                            <strong>Sistema con validación automática:</strong><br>
                                                            • Presencial: Solo martes-viernes (10:00 AM - 4:30 PM)<br>
                                                            • Remota: Solo lunes-viernes (9:00 AM - 5:30 PM)<br>
                                                            • Horarios optimizados según modalidad
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
    </div>
</div>

<!-- Modal para Agendar Cita -->
<div class="modal fade" id="agendarModal" tabindex="-1" aria-labelledby="agendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agendarModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Agendar Cita Múltiple
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalAgendarForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles de la Cita:</label>
                        <div class="p-3 bg-light rounded">
                            <div class="alert alert-success mb-2">
                                <small><i class="fas fa-check-circle me-1"></i> El evento se creará automáticamente en <strong>TODOS</strong> los calendarios de los colaboradores seleccionados y se enviará una invitación a cada uno.</small>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Colaboradores:</strong></div>
                                <div class="col-sm-8" id="modalColaboradores">-</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Fecha:</strong></div>
                                <div class="col-sm-8" id="modalFecha">-</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Horario:</strong></div>
                                <div class="col-sm-8" id="modalHorario">-</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Modalidad:</strong></div>
                                <div class="col-sm-8" id="modalModalidad">-</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalTitulo" class="form-label fw-bold">Título de la Cita *</label>
                        <input type="text" class="form-control" id="modalTitulo" placeholder="Ej: Reunión de proyecto" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalDescripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="modalDescripcion" rows="3" placeholder="Describe brevemente el tema de la reunión (opcional)"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalEmail" class="form-label fw-bold">Tu Email *</label>
                        <input type="email" class="form-control" id="modalEmail" placeholder="ejemplo@empresa.com" required>
                        <small class="text-muted">El evento se enviará tanto a tu email como al de todos los colaboradores seleccionados</small>
                        <div id="emailError" class="invalid-feedback" style="display: none;">
                            Email no válido. Solo se permiten emails de los colaboradores de Fiborti.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalNombre" class="form-label fw-bold">Tu Nombre</label>
                        <input type="text" class="form-control" id="modalNombre" placeholder="Tu nombre completo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarAgendar">
                    <i class="fas fa-calendar-plus me-2"></i>Agendar en Todos los Calendarios
                </button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
