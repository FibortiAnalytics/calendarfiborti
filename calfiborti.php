<?php
/**
 * Template Name: Calendario Fiborti
 * Description: Página especial para el sistema de calendario de Fiborti
 */

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
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="modalidad" id="remota" value="remota" required>
                                                                <label class="form-check-label" for="remota">
                                                                    <i class="fas fa-video me-1"></i> Remota
                                                                </label>
                                                            </div>
                                                        </div>
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
                                                        </div>
                                                        <small class="text-muted">Selecciona uno o múltiples colaboradores para consultar disponibilidad</small>
                                                    </div>

                                                    <!-- Fecha -->
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Fecha de la reunión *</label>
                                                        <input type="date" class="form-control" id="fechaReunion" min="<?php echo date('Y-m-d'); ?>" required>
                                                    </div>

                                                    <!-- Hora (opcional) -->
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Hora específica (opcional)</label>
                                                        <input type="time" class="form-control" id="horaReunion">
                                                        <small class="text-muted">Si no especificas hora, se mostrará la disponibilidad de todo el día</small>
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
                                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Resultado de Disponibilidad</h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="disponibilidadResultado">
                                                    <div class="text-center text-muted py-5">
                                                        <i class="fas fa-calendar-check fa-4x mb-3"></i>
                                                        <h5>Consulta la disponibilidad</h5>
                                                        <p>Selecciona uno o múltiples colaboradores en el formulario de la izquierda para ver su disponibilidad</p>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agendarModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Agendar Cita
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalAgendarForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles de la Cita:</label>
                        <div class="p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-sm-4"><strong>Colaborador:</strong></div>
                                <div class="col-sm-8" id="modalPersona">-</div>
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
                        <label for="modalAsistente" class="form-label fw-bold">Tu Nombre</label>
                        <input type="text" class="form-control" id="modalAsistente" placeholder="Tu nombre">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmarAgendar">
                    <i class="fas fa-calendar-plus me-2"></i>Confirmar Agendamiento
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Ocultar header y footer en esta página */
body.page-template-calfiborti header,
body.page-template-calfiborti .site-header,
body.page-template-calfiborti .navbar,
body.page-template-calfiborti footer,
body.page-template-calfiborti .footer-area,
body.page-template-calfiborti .site-footer {
    display: none !important;
}

/* Ocultar chatbot y elementos relacionados */
body.page-template-calfiborti .fiborti-chatbot-container,
body.page-template-calfiborti .fiborti-chatbot-toggle,
body.page-template-calfiborti .fiborti-chatbot-window,
body.page-template-calfiborti #fibortiChatbotToggle,
body.page-template-calfiborti #fibortiChatbotWindow,
body.page-template-calfiborti [id*="chatbot"],
body.page-template-calfiborti [class*="chatbot"],
body.page-template-calfiborti [id*="chat"],
body.page-template-calfiborti [class*="chat-widget"],
body.page-template-calfiborti .chat-button,
body.page-template-calfiborti .chat-toggle,
body.page-template-calfiborti .widget-chat {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* Ajustar el contenido para que ocupe toda la pantalla */
body.page-template-calfiborti {
    margin: 0 !important;
    padding: 0 !important;
}

body.page-template-calfiborti .container.content-area {
    margin-top: 0 !important;
    padding-top: 2rem !important;
    max-width: 100% !important;
}

/* Centrar el título */
body.page-template-calfiborti h1.card-title {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
    font-size: 2.5rem;
}

.fiborti-availability-section {
    margin-top: 2rem;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-label {
    cursor: pointer;
    font-weight: 500;
}

.form-check {
    margin-bottom: 0.75rem;
}

.disponibilidad-slot {
    padding: 12px 16px;
    margin: 8px 0;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.disponibilidad-slot.libre {
    background-color: #d1edff;
    border-color: #007bff;
    cursor: pointer;
}

.disponibilidad-slot.libre:hover {
    background-color: #b3d9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.disponibilidad-slot.ocupado {
    background-color: #f8d7da;
    border-color: #dc3545;
    cursor: not-allowed;
}

.loading-spinner {
    display: inline-block;
    width: 24px;
    height: 24px;
    border: 3px solid rgba(0,0,0,.2);
    border-radius: 50%;
    border-top-color: #007bff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.btn-lg {
    font-weight: 600;
    padding: 12px 24px;
}

.border-primary {
    border-color: #007bff !important;
}

.border-success {
    border-color: #198754 !important;
}

/* Estilos específicos para checkboxes de colaboradores */
.form-check-input[type="checkbox"] {
    width: 1.2em;
    height: 1.2em;
}

.form-check-label {
    padding-left: 0.5rem;
    display: flex;
    align-items: center;
}

/* Efecto visual para colaboradores seleccionados */
.form-check-input[type="checkbox"]:checked + .form-check-label {
    color: #007bff;
    font-weight: 600;
}

/* Indicador visual de múltiples selecciones */
.colaboradores-seleccionados {
    background-color: #e7f3ff;
    border: 1px solid #007bff;
    border-radius: 4px;
    padding: 8px 12px;
    margin-top: 10px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .form-check {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

<script>
// URL del webhook de n8n - CAMBIA ESTA URL POR LA DE TU WEBHOOK
const WEBHOOK_URL = 'https://n8n.srv915832.hstgr.cloud/webhook/fiborti-availability';

// Variables globales
let datosAgendamiento = {};

// Configurar fechas por defecto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fechaReunion').value = today;
    
    // Agregar evento para mostrar colaboradores seleccionados
    const checkboxes = document.querySelectorAll('input[name="colaboradores[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', mostrarColaboradoresSeleccionados);
    });
});

// Función para mostrar colaboradores seleccionados
function mostrarColaboradoresSeleccionados() {
    const checkboxes = document.querySelectorAll('input[name="colaboradores[]"]:checked');
    const container = document.querySelector('.form-check:last-of-type').parentElement;
    
    // Remover indicador anterior si existe
    const existing = container.querySelector('.colaboradores-seleccionados');
    if (existing) existing.remove();
    
    if (checkboxes.length > 0) {
        const nombres = Array.from(checkboxes).map(cb => cb.nextElementSibling.textContent.trim());
        const indicator = document.createElement('div');
        indicator.className = 'colaboradores-seleccionados';
        indicator.innerHTML = `<i class="fas fa-check-circle me-1"></i><strong>Seleccionados (${checkboxes.length}):</strong> ${nombres.join(', ')}`;
        container.appendChild(indicator);
    }
}

// FUNCIÓN PRINCIPAL: Consultar disponibilidad
document.getElementById('disponibilidadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Recolectar datos del formulario
    const formData = new FormData(this);
    const modalidad = formData.get('modalidad');
    const fecha = document.getElementById('fechaReunion').value;
    const hora = document.getElementById('horaReunion').value;
    
    // Obtener colaboradores seleccionados
    const colaboradoresSeleccionados = Array.from(document.querySelectorAll('input[name="colaboradores[]"]:checked'))
        .map(checkbox => checkbox.value);
    
    // Validaciones
    if (!modalidad) {
        alert('Por favor selecciona la modalidad de la reunión');
        return;
    }
    
    if (colaboradoresSeleccionados.length === 0) {
        alert('Por favor selecciona al menos un colaborador');
        return;
    }
    
    if (!fecha) {
        alert('Por favor selecciona una fecha');
        return;
    }
    
    // Construir objeto JSON para enviar a n8n
    const requestData = {
        action: 'consultar_disponibilidad',
        modalidad: modalidad,
        colaboradores: colaboradoresSeleccionados,
        fecha: fecha,
        hora: hora || null,
        timestamp: new Date().toISOString(),
        source: 'wordpress_form'
    };
    
    console.log('Enviando datos a n8n:', requestData);
    
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    
    // Mostrar loading
    resultadoDiv.innerHTML = `
        <div class="text-center py-4">
            <div class="loading-spinner mb-3"></div>
            <h5>Consultando disponibilidad...</h5>
            <p class="text-muted">Revisando calendarios de ${colaboradoresSeleccionados.join(', ')}, por favor espera</p>
        </div>
    `;
    
    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Obtener el texto de la respuesta primero
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        // Verificar si la respuesta está vacía
        if (!responseText || responseText.trim() === '') {
            throw new Error('Respuesta vacía del servidor');
        }
        
        // Intentar parsear como JSON
        let responseData;
        try {
            responseData = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('Error parsing JSON:', jsonError);
            throw new Error('La respuesta del servidor no es JSON válido: ' + responseText.substring(0, 100));
        }
        
        console.log('Respuesta de n8n:', responseData);
        
        mostrarDisponibilidad(responseData, requestData);
        
    } catch (error) {
        console.error('Error:', error);
        resultadoDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error al consultar disponibilidad</strong><br>
                ${error.message}<br>
                <small class="text-muted">Por favor, verifica tu conexión e intenta de nuevo.</small>
            </div>
        `;
    }
});

// Función para mostrar los resultados de disponibilidad
function mostrarDisponibilidad(responseData, requestData) {
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    
    const iconoModalidad = requestData.modalidad === 'presencial' ? 'fas fa-users' : 'fas fa-video';
    const colaboradoresTexto = requestData.colaboradores.join(', ');
    
    // Header con información de la consulta
    let html = `
        <div class="mb-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="${iconoModalidad} me-2"></i>
                        Consulta: ${requestData.modalidad.charAt(0).toUpperCase() + requestData.modalidad.slice(1)} - Múltiples Colaboradores
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>Fecha:</strong></div>
                        <div class="col-sm-8">${new Date(requestData.fecha).toLocaleDateString('es-ES', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Colaboradores:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-primary me-1">${requestData.colaboradores.length}</span>
                            ${colaboradoresTexto}
                        </div>
                    </div>
                    ${requestData.hora ? `
                    <div class="row">
                        <div class="col-sm-4"><strong>Hora:</strong></div>
                        <div class="col-sm-8">${requestData.hora}</div>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    // Verificar si responseData tiene la estructura esperada
    if (!responseData || typeof responseData !== 'object') {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Respuesta inesperada del servidor</strong><br>
                El servidor devolvió una respuesta en formato incorrecto.
            </div>
        `;
        resultadoDiv.innerHTML = html;
        return;
    }
    
    // Procesar respuesta de n8n
    if (responseData.success) {
        html += `<div class="alert alert-success">
            <h6><i class="fas fa-check-circle me-2"></i>Análisis de disponibilidad completado:</h6>
        </div>`;
        
        // Mostrar horarios disponibles
        if (responseData.disponibilidad && Array.isArray(responseData.disponibilidad) && responseData.disponibilidad.length > 0) {
            html += `<div class="mb-3">
                <h6>Horarios analizados:</h6>`;
            
            responseData.disponibilidad.forEach(slot => {
                const claseSlot = slot.disponible ? 'libre' : 'ocupado';
                const iconoSlot = slot.disponible ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';
                
                html += `
                    <div class="disponibilidad-slot ${claseSlot}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="${iconoSlot} me-2"></i>${slot.hora_inicio || 'N/A'} - ${slot.hora_fin || 'N/A'}</span>
                            <div>
                                <span class="badge ${slot.disponible ? 'bg-success' : 'bg-danger'} me-2">
                                    ${slot.disponible ? 'Disponible para todos' : 'Conflicto detectado'}
                                </span>
                                ${slot.disponible && requestData.colaboradores.length === 1 ? `
                                    <button class="btn btn-primary btn-sm" onclick="agendarHorario('${slot.hora_inicio}', '${slot.hora_fin}', '${requestData.colaboradores[0]}', '${requestData.fecha}', '${requestData.modalidad}')">
                                        <i class="fas fa-calendar-plus me-1"></i>Agendar
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                        ${slot.conflictos ? `<small class="text-muted">Conflictos: ${slot.conflictos}</small>` : ''}
                        ${slot.evento ? `<small class="text-muted">Evento: ${slot.evento}</small>` : ''}
                    </div>
                `;
            });
            
            html += `</div>`;
        } else {
            // Si no hay disponibilidad estructurada, mostrar solo el mensaje
            html += `
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Información de disponibilidad:</h6>
                    <p>No se encontraron horarios específicos, pero el sistema devolvió la siguiente información:</p>
                </div>
            `;
        }
        
        // Mostrar mensaje de respuesta
        if (responseData.mensaje) {
            html += `
                <div class="alert alert-light">
                    <h6><i class="fas fa-info-circle me-2"></i>Resumen del análisis:</h6>
                    <div class="mt-2">${responseData.mensaje}</div>
                </div>
            `;
        }
    } else {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>No se pudo obtener la disponibilidad</strong><br>
                ${responseData.mensaje || 'Error desconocido'}
            </div>
        `;
    }
    
    html += `
        <div class="mt-3 p-3 bg-light rounded">
            <small class="text-muted">
                <i class="fas fa-lightbulb me-1"></i>
                <strong>Tip:</strong> ${requestData.colaboradores.length > 1 ? 
                    'Cuando seleccionas múltiples colaboradores, el sistema muestra los horarios donde todos están disponibles.' : 
                    'Haz clic en el botón "Agendar" de cualquier horario disponible para reservar tu cita.'}
            </small>
        </div>
    `;
    
    resultadoDiv.innerHTML = html;
}

// Función para abrir el modal de agendamiento (solo para un colaborador)
function agendarHorario(horaInicio, horaFin, persona, fecha, modalidad) {
    // Guardar datos en variables globales
    datosAgendamiento = {
        persona: persona,
        fecha: fecha,
        horaInicio: horaInicio,
        horaFin: horaFin,
        modalidad: modalidad
    };
    
    // Actualizar modal con información
    document.getElementById('modalPersona').textContent = persona;
    document.getElementById('modalFecha').textContent = new Date(fecha).toLocaleDateString('es-ES', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    document.getElementById('modalHorario').textContent = `${horaInicio} - ${horaFin}`;
    document.getElementById('modalModalidad').textContent = modalidad.charAt(0).toUpperCase() + modalidad.slice(1);
    
    // Limpiar formulario
    document.getElementById('modalTitulo').value = '';
    document.getElementById('modalAsistente').value = '';
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('agendarModal'));
    modal.show();
}

// Manejar confirmación de agendamiento
document.getElementById('confirmarAgendar').addEventListener('click', async function() {
    const titulo = document.getElementById('modalTitulo').value.trim();
    const asistente = document.getElementById('modalAsistente').value.trim();
    
    if (!titulo) {
        alert('Por favor ingresa un título para la cita');
        return;
    }
    
    const requestData = {
        action: 'agendar_cita',
        persona: datosAgendamiento.persona,
        fecha: datosAgendamiento.fecha,
        hora_inicio: datosAgendamiento.horaInicio,
        hora_fin: datosAgendamiento.horaFin,
        titulo: titulo,
        asistente: asistente,
        modalidad: datosAgendamiento.modalidad,
        timestamp: new Date().toISOString(),
        source: 'wordpress_form'
    };
    
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<div class="loading-spinner me-2"></div> Agendando...';
    btn.disabled = true;
    
    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Agendar - Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Obtener el texto de la respuesta primero
        const responseText = await response.text();
        console.log('Agendar - Response text:', responseText);
        
        // Verificar si la respuesta está vacía
        if (!responseText || responseText.trim() === '') {
            throw new Error('Respuesta vacía del servidor');
        }
        
        // Intentar parsear como JSON
        let responseData;
        try {
            responseData = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('Error parsing JSON en agendamiento:', jsonError);
            throw new Error('La respuesta del servidor no es JSON válido');
        }
        
        if (responseData.success) {
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('agendarModal'));
            modal.hide();
            
            // Mostrar mensaje de éxito
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Cita agendada exitosamente!</strong><br>
                <small>${responseData.mensaje || 'La cita ha sido registrada en el calendario.'}</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insertar después del título
            const title = document.querySelector('h1.card-title');
            title.parentNode.insertBefore(alertDiv, title.nextSibling);
            
            // Actualizar la consulta para mostrar el nuevo evento
            setTimeout(() => {
                const consultarBtn = document.querySelector('#disponibilidadForm button[type="submit"]');
                if (consultarBtn) {
                    consultarBtn.click();
                }
            }, 1000);
            
        } else {
            alert('Error: ' + (responseData.mensaje || 'No se pudo agendar la cita'));
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al agendar la cita. Por favor, intenta de nuevo.');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
</script>

<?php get_footer(); ?>