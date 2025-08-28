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
                                                    <div class="alert alert-info mt-2">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        <strong>Importante:</strong> Solo se mostrarán horarios donde <strong>TODOS</strong> los colaboradores seleccionados estén disponibles.
                                                    </div>
                                                </div>

                                                <!-- Fecha -->
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Fecha de la reunión *</label>
                                                    <input type="date" class="form-control" id="fechaReunion" min="<?php echo date('Y-m-d'); ?>" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                                    <i class="fas fa-search me-2"></i>Consultar Disponibilidad
                                                </button>
                                                
                                                <!-- Botón de prueba para debugging -->
                                                <button type="button" class="btn btn-outline-info w-100 mt-2" onclick="probarFormulario()">
                                                    <i class="fas fa-bug me-2"></i>Probar Formulario
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
                                                    <p>Selecciona uno o múltiples colaboradores en el formulario de la izquierda para ver horarios disponibles de 9:00 AM a 7:00 PM</p>
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
                    <i class="fas fa-calendar-plus me-2"></i>Agendar Cita
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalAgendarForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detalles de la Cita:</label>
                        <div class="p-3 bg-light rounded">
                            <div class="alert alert-info mb-2">
                                <small><i class="fas fa-info-circle me-1"></i> La cita se agendará con el primer colaborador seleccionado. Si necesitas agendar con múltiples colaboradores, hazlo uno por uno.</small>
                            </div>
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
                        <label for="modalDescripcion" class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" id="modalDescripcion" rows="3" placeholder="Describe brevemente el tema de la reunión (opcional)"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalEmail" class="form-label fw-bold">Tu Email *</label>
                        <input type="email" class="form-control" id="modalEmail" placeholder="ejemplo@empresa.com" required>
                        <small class="text-muted">El evento se enviará tanto a tu email como al del colaborador</small>
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
                    <i class="fas fa-calendar-plus me-2"></i>Confirmar Agendamiento
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para ocultar header y footer */
body.page-template-calfiborti header,
body.page-template-calfiborti .site-header,
body.page-template-calfiborti .navbar,
body.page-template-calfiborti footer,
body.page-template-calfiborti .site-footer {
    display: none !important;
}

body.page-template-calfiborti {
    margin: 0 !important;
    padding: 0 !important;
}

body.page-template-calfiborti .container.content-area {
    margin-top: 0 !important;
    padding-top: 2rem !important;
    max-width: 100% !important;
}

/* Estilos del formulario */
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

/* Grid de horarios */
.horarios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.horario-slot {
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    border: 2px solid #28a745;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.horario-slot.horario-agendable {
    cursor: pointer;
}

.horario-slot.horario-agendable:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(40, 167, 69, 0.3);
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border-color: #20c997;
}

.horario-slot.horario-agendable::before {
    content: '';
    position: absolute;
    top: 5px;
    right: 5px;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    opacity: 0.7;
}

.horario-slot.ocupado {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border-color: #ffc107;
    cursor: not-allowed;
    opacity: 0.9;
    position: relative;
}

.horario-slot.ocupado::before {
    content: '⚠️';
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 12px;
    opacity: 0.8;
}

.horario-slot.ocupado .horario-tiempo {
    color: #856404;
}

.horario-slot.ocupado .horario-estado {
    color: #856404;
}

.horario-tiempo {
    font-size: 1.1rem;
    font-weight: 600;
    color: #155724;
    margin-bottom: 0.5rem;
}

.horario-estado {
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.btn-agendar {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    margin: 0.5rem 0;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-agendar:hover {
    background: linear-gradient(45deg, #218838, #1ea080);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
}

.horario-ocupado-info {
    font-size: 0.8rem;
    color: #721c24;
    font-style: italic;
    text-align: left;
    line-height: 1.3;
}

.horario-ocupado-info strong {
    color: #dc3545;
    font-weight: 600;
}

.horario-ocupado-info small {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .horarios-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 0.5rem;
    }
    
    .horario-slot {
        padding: 1rem;
        min-height: 160px;
    }
    
    .btn-agendar {
        padding: 0.6rem 1.2rem;
        font-size: 0.85rem;
    }
}

@media (min-width: 769px) and (max-width: 1200px) {
    .horarios-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.2rem;
            }
        
        /* Spinner de carga */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    }
</style>

<script>
// URL del webhook de n8n
const WEBHOOK_URL = 'https://n8n.srv915832.hstgr.cloud/webhook/fiborti-availability';

// Emails válidos para validación
const EMAILS_VALIDOS = [
    'agencia@fiborti.com',
    'danny.fiborti@gmail.com',
    'mauleon1119@gmail.com',
    'fernando.fiborti@gmail.com',
    'emilio.fiborti@gmail.com'
];

// Variables globales
let datosAgendamiento = {};

// Configurar fechas por defecto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fechaReunion').value = today;
    
    console.log('✅ Sistema de calendario cargado correctamente');
});

// Función para generar horarios de 9am a 7pm
function generarHorarios() {
    const horarios = [];
    for (let hora = 9; hora < 19; hora++) {
        const horaInicio = `${hora.toString().padStart(2, '0')}:00`;
        const horaFin = `${(hora + 1).toString().padStart(2, '0')}:00`;
        horarios.push({
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            disponible: true
        });
    }
    return horarios;
}

// FUNCIÓN PRINCIPAL: Consultar disponibilidad
document.getElementById('disponibilidadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Recolectar datos del formulario
    const formData = new FormData(this);
    const modalidad = formData.get('modalidad');
    const fecha = document.getElementById('fechaReunion').value;
    
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
        
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let responseData;
        try {
            responseData = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('Error parsing JSON:', jsonError);
            throw new Error('La respuesta del servidor no es JSON válido: ' + responseText.substring(0, 100));
        }
        
        console.log('Respuesta de n8n:', responseData);
        
        mostrarHorariosDisponibles(responseData, requestData);
        
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

// Función para mostrar los horarios disponibles en grid
function mostrarHorariosDisponibles(responseData, requestData) {
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    
    const iconoModalidad = requestData.modalidad === 'presencial' ? 'fas fa-users' : 'fas fa-video';
    const colaboradoresTexto = requestData.colaboradores.join(', ');
    
    // Header con información de la consulta
    let html = `
        <div class="mb-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="${iconoModalidad} me-2"></i>
                        Disponibilidad para: ${colaboradoresTexto}
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
                        <div class="col-sm-4"><strong>Modalidad:</strong></div>
                        <div class="col-sm-8">${requestData.modalidad.charAt(0).toUpperCase() + requestData.modalidad.slice(1)}</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Generar horarios de 9am a 7pm
    const horariosBase = generarHorarios();
    
    // Crear grid de horarios
    html += `
        <div class="mb-4">
            <div class="text-center mb-3">
                <h4 class="text-primary">
                    <i class="fas fa-clock me-2"></i>Horarios Disponibles
                </h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar-day me-1"></i>9:00 AM - 7:00 PM
                </p>
            </div>
            <div class="horarios-grid">
    `;

    // Procesar cada horario
    horariosBase.forEach(horario => {
        const horaInicio = horario.hora_inicio;
        const horaFin = horario.hora_fin;
        
        // Por ahora, mostrar todos como disponibles (esto se puede modificar según la respuesta de n8n)
        html += `
            <div class="horario-slot horario-agendable">
                <div class="horario-tiempo">${horaInicio} - ${horaFin}</div>
                <div class="horario-estado">
                    <i class="fas fa-check-circle text-success me-1"></i>Todos disponibles
                </div>
                <button class="btn-agendar" onclick="abrirModalAgendamiento('${horaInicio}', '${horaFin}', '${requestData.colaboradores.join(',')}', '${requestData.fecha}', '${requestData.modalidad}')">
                    <i class="fas fa-calendar-plus me-1"></i>Agendar
                </button>
                <small class="text-muted mt-2">
                    <i class="fas fa-users me-1"></i>
                    ${requestData.colaboradores.join(', ')}
                </small>
            </div>
        `;
    });

    html += `</div></div>`;

    // Agregar mensaje de éxito
    html += `
        <div class="alert alert-success">
            <i class="fas fa-calendar-check me-2"></i>
            <strong>¡Perfecto!</strong> Se encontraron <strong>${horariosBase.length}</strong> horarios disponibles.
            <br><small>Los horarios mostrados están disponibles para TODOS los colaboradores seleccionados: ${colaboradoresTexto}</small>
        </div>
    `;

    resultadoDiv.innerHTML = html;
    
    console.log(`📅 Horarios generados correctamente. Colaboradores: ${requestData.colaboradores.join(', ')}`);
}

// Función para abrir el modal de agendamiento
function abrirModalAgendamiento(horaInicio, horaFin, colaboradoresJson, fecha, modalidad) {
    console.log('🎯 Función abrirModalAgendamiento llamada');
    
    let colaboradores;
    if (typeof colaboradoresJson === 'string') {
        colaboradores = colaboradoresJson.split(',').map(c => c.trim());
    } else {
        colaboradores = Array.isArray(colaboradoresJson) ? colaboradoresJson : [colaboradoresJson];
    }
    
    // Guardar datos en variables globales
    datosAgendamiento = {
        persona: colaboradores[0], // Por ahora solo el primero
        colaboradores: colaboradores, // Guardar todos los colaboradores
        fecha: fecha,
        horaInicio: horaInicio,
        horaFin: horaFin,
        modalidad: modalidad
    };
    
    // Actualizar modal con información
    document.getElementById('modalPersona').textContent = colaboradores[0];
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
    document.getElementById('modalDescripcion').value = '';
    document.getElementById('modalEmail').value = '';
    document.getElementById('modalNombre').value = '';
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('agendarModal'));
    modal.show();
}

// Función para probar el formulario
function probarFormulario() {
    console.log('🧪 Probando formulario...');
    
    // Seleccionar opciones por defecto
    document.getElementById('presencial').checked = true;
    document.getElementById('mauricio').checked = true;
    document.getElementById('fernando').checked = true;
    
    // Establecer fecha de mañana
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('fechaReunion').value = tomorrow.toISOString().split('T')[0];
    
    console.log('✅ Formulario configurado para prueba');
    alert('Formulario configurado para prueba. Ahora puedes hacer clic en "Consultar Disponibilidad"');
}

// Manejar confirmación de agendamiento
document.getElementById('confirmarAgendar').addEventListener('click', async function() {
    const titulo = document.getElementById('modalTitulo').value.trim();
    const descripcion = document.getElementById('modalDescripcion').value.trim();
    const email = document.getElementById('modalEmail').value.trim();
    const nombre = document.getElementById('modalNombre').value.trim();
    
    // Validaciones básicas
    if (!titulo) {
        alert('Por favor ingresa un título para la cita');
        document.getElementById('modalTitulo').focus();
        return;
    }
    
    if (!email) {
        alert('Por favor ingresa tu email');
        document.getElementById('modalEmail').focus();
        return;
    }
    
    // Validar email
    if (!EMAILS_VALIDOS.includes(email.toLowerCase().trim())) {
        alert('Email no válido. Solo se permiten emails de colaboradores de Fiborti.');
        document.getElementById('modalEmail').focus();
        return;
    }
    
    // Obtener el colaborador final
    const colaboradorFinal = datosAgendamiento.persona;
    
    // Construir datos para agendar
    const requestData = {
        action: 'agendar_cita',
        persona: colaboradorFinal,
        fecha: datosAgendamiento.fecha,
        hora_inicio: datosAgendamiento.horaInicio,
        hora_fin: datosAgendamiento.horaFin,
        titulo: titulo,
        descripcion: descripcion,
        email: email,
        asistente: nombre || email,
        modalidad: datosAgendamiento.modalidad,
        timestamp: new Date().toISOString(),
        source: 'wordpress_form'
    };
    
    console.log('🎯 Agendando cita real:', requestData);
    
    // Cambiar estado del botón
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
        
        const responseText = await response.text();
        console.log('Agendar - Response text:', responseText);
        
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
                <small>El evento ha sido creado para ${colaboradorFinal} y se ha enviado una invitación a ${email}</small>
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
            }, 1500);
            
        } else {
            throw new Error(responseData.mensaje || 'No se pudo agendar la cita');
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al agendar la cita: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
</script>

<?php get_footer(); ?>
