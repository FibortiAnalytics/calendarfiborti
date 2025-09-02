// ===========================================
// SISTEMA DE CALENDARIO FIBORTI - CÓDIGO LIMPIO
// ===========================================

// URL del webhook de n8n
const WEBHOOK_URL = 'https://n8n.srv915832.hstgr.cloud/webhook/fiborti-availability-v2';

// Emails válidos para validación
const EMAILS_VALIDOS = [
    'agencia@fiborti.com',
    'danny.fiborti@gmail.com',
    'mauleon1119@gmail.com',
    'fernando.fiborti@gmail.com',
    'emilio.fiborti@gmail.com'
];

// Mapeo de emails a nombres de colaboradores
const EMAIL_TO_COLABORADOR = {
    'agencia@fiborti.com': 'Fiborti Team',
    'danny.fiborti@gmail.com': 'Danny',
    'mauleon1119@gmail.com': 'Mauricio',
    'fernando.fiborti@gmail.com': 'Fernando',
    'emilio.fiborti@gmail.com': 'Emilio'
};

// Variable global para datos de agendamiento
let datosAgendamiento = {};

// ===========================================
// FUNCIONES AUXILIARES - FORMATO
// ===========================================

function formatearFechaMexicana(fecha) {
    let date;
    if (typeof fecha === 'string') {
        const [year, month, day] = fecha.split('-').map(Number);
        date = new Date(year, month - 1, day);
    } else if (fecha instanceof Date) {
        date = fecha;
    } else {
        date = new Date(fecha);
    }
    
    return date.toLocaleDateString('es-MX', {
        weekday: 'long',
        year: 'numeric', 
        month: 'long',
        day: 'numeric',
        timeZone: 'America/Mexico_City'
    });
}

function formatearHoraMexicana(hora) {
    const [hours, minutes] = hora.split(':').map(Number);
    const date = new Date();
    date.setHours(hours, minutes);
    
    return date.toLocaleTimeString('es-MX', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
        timeZone: 'America/Mexico_City'
    });
}

function formatearRangoHoraMexicana(horaInicio, horaFin) {
    return `${formatearHoraMexicana(horaInicio)} - ${formatearHoraMexicana(horaFin)}`;
}

// ===========================================
// INICIALIZACIÓN
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha por defecto
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fechaReunion').value = today;
    
    // Configurar selector de duración según modalidad
    const modalidadRadios = document.querySelectorAll('input[name="modalidad"]');
    const duracionSection = document.getElementById('duracionSection');
    
    modalidadRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'remota') {
                duracionSection.style.display = 'block';
                duracionSection.classList.add('visible');
                document.querySelectorAll('input[name="duracion"]').forEach(d => d.required = true);
            } else {
                duracionSection.style.display = 'none';
                duracionSection.classList.remove('visible');
                document.querySelectorAll('input[name="duracion"]').forEach(d => {
                    d.required = false;
                    d.checked = false;
                });
            }
        });
    });
    
    console.log('Sistema de calendario cargado correctamente');
});

// ===========================================
// VALIDACIÓN DE DÍAS
// ===========================================

function validarDiaSegunModalidad(fecha, modalidad) {
    // Parseo seguro de YYYY-MM-DD evitando desplazamientos por zona horaria
    // Crear Date con componentes locales (año, mes, día)
    let fechaObj;
    if (typeof fecha === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
        const [y, m, d] = fecha.split('-').map(Number);
        fechaObj = new Date(y, m - 1, d, 12, 0, 0, 0); // mediodía local para evitar DST edge cases
    } else if (fecha instanceof Date) {
        fechaObj = fecha;
    } else {
        fechaObj = new Date(fecha);
    }
    const dayOfWeek = fechaObj.getDay();
    const dayNames = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    
    // Reuniones presenciales: martes a viernes (2,3,4,5)
    if (modalidad === 'presencial' && ![2, 3, 4, 5].includes(dayOfWeek)) {
        return {
            valido: false,
            mensaje: `Día no permitido para reuniones presenciales: ${dayNames[dayOfWeek]}`,
            solucion: 'presencial'
        };
    }
    
    // Reuniones remotas: lunes a viernes (1,2,3,4,5)
    if (modalidad === 'remota' && ![1, 2, 3, 4, 5].includes(dayOfWeek)) {
        return {
            valido: false,
            mensaje: `Día no permitido para reuniones remotas: ${dayNames[dayOfWeek]}`,
            solucion: 'remota'
        };
    }
    

    
    return { valido: true };
}

function mostrarErrorValidacion(resultado, fechaObj) {
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    const dayNames = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    
    resultadoDiv.innerHTML = `
        <div class="alert alert-danger error-validation">
            <div class="text-center mb-3">
                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
            </div>
            <h5 class="text-center mb-3">❌ ${resultado.mensaje}</h5>
            <div class="text-center mb-3">
                <strong>Fecha seleccionada:</strong> ${dayNames[fechaObj.getDay()]}, ${formatearFechaMexicana(fechaObj)}<br>
                <small class="text-muted">Las reuniones ${resultado.solucion} solo se permiten ${resultado.solucion === 'presencial' ? 'martes a viernes' : 'lunes a viernes'}</small>
            </div>
            <div class="mt-4 p-3 bg-light rounded">
                <h6><i class="fas fa-lightbulb me-2"></i>Soluciones:</h6>
                <div class="suggestion-buttons text-center">
                    ${resultado.solucion === 'presencial' ? `
                        <button class="btn btn-outline-primary" onclick="cambiarARemota()">
                            <i class="fas fa-video me-1"></i>Cambiar a Remota
                        </button>
                    ` : ''}
                    <button class="btn btn-outline-success" onclick="sugerirFechasValidas('${resultado.solucion}')">
                        <i class="fas fa-calendar-alt me-1"></i>Ver Fechas Válidas
                    </button>
                </div>
            </div>
        </div>
    `;
}

// ===========================================
// CONSULTA PRINCIPAL
// ===========================================

document.getElementById('disponibilidadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Recolectar datos
    const formData = new FormData(this);
    const modalidad = formData.get('modalidad');
    const fecha = document.getElementById('fechaReunion').value;
    const duracion = formData.get('duracion');
    const colaboradoresSeleccionados = Array.from(document.querySelectorAll('input[name="colaboradores[]"]:checked'))
        .map(checkbox => checkbox.value);
    
    // Validaciones básicas
    if (!modalidad) {
        alert('Por favor selecciona la modalidad de la reunión');
        return;
    }
    
    if (modalidad === 'remota' && !duracion) {
        alert('Por favor selecciona la duración para la reunión remota');
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
    
    // Validar día según modalidad
    const fechaObj = new Date(fecha);
    const validacion = validarDiaSegunModalidad(fecha, modalidad);
    
    if (!validacion.valido) {
        mostrarErrorValidacion(validacion, fechaObj);
        return;
    }
    
    // Construir request
    const requestData = {
        action: 'consultar_disponibilidad',
        modalidad: modalidad,
        colaboradores: colaboradoresSeleccionados,
        fecha: fecha,
        duracion: duracion || (modalidad === 'presencial' ? '60' : null),
        timestamp: new Date().toISOString(),
        source: 'wordpress_form'
    };
    
    console.log('Enviando datos a n8n:', requestData);
    
    // Limpiar cuadros de debug anteriores
    const debugEnviadoAnterior = document.getElementById('debugEnviado');
    const debugRespuestaAnterior = document.getElementById('debugRespuesta');
    if (debugEnviadoAnterior) debugEnviadoAnterior.remove();
    if (debugRespuestaAnterior) debugRespuestaAnterior.remove();
    
    // Mostrar loading
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    resultadoDiv.innerHTML = `
        <div class="text-center py-4">
            <div class="loading-spinner mb-3"></div>
            <h5>Consultando disponibilidad...</h5>
            <p class="text-muted">Revisando calendarios de ${colaboradoresSeleccionados.join(', ')}</p>
        </div>
    `;
    
    // Crear cuadro de debug permanente para datos enviados (OCULTO PARA PRESENTACIÓN)
    const debugEnviadoDiv = document.createElement('div');
    debugEnviadoDiv.id = 'debugEnviado';
    debugEnviadoDiv.className = 'mb-4';
    debugEnviadoDiv.style.display = 'none'; // OCULTO
    debugEnviadoDiv.innerHTML = `
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-bug me-2"></i>Debug - Datos Enviados al Webhook</h6>
            </div>
            <div class="card-body">
                <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">${JSON.stringify(requestData, null, 2)}</pre>
            </div>
        </div>
    `;
    
    // Insertar el debug después del resultado
    resultadoDiv.parentNode.insertBefore(debugEnviadoDiv, resultadoDiv.nextSibling);
    
    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });
        
        const responseText = await response.text();
        const responseData = JSON.parse(responseText);
        
        // Crear cuadro de debug permanente para respuesta del webhook (OCULTO PARA PRESENTACIÓN)
        const debugRespuestaDiv = document.createElement('div');
        debugRespuestaDiv.id = 'debugRespuesta';
        debugRespuestaDiv.className = 'mb-4';
        debugRespuestaDiv.style.display = 'none'; // OCULTO
        debugRespuestaDiv.innerHTML = `
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-reply me-2"></i>Debug - Respuesta del Webhook</h6>
                </div>
                <div class="card-body">
                    <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">${JSON.stringify(responseData, null, 2)}</pre>
                </div>
            </div>
        `;
        
        // Insertar el debug después del cuadro de datos enviados
        const debugEnviadoDiv = document.getElementById('debugEnviado');
        if (debugEnviadoDiv && debugEnviadoDiv.nextSibling) {
            debugEnviadoDiv.parentNode.insertBefore(debugRespuestaDiv, debugEnviadoDiv.nextSibling);
        } else {
            debugEnviadoDiv.parentNode.appendChild(debugRespuestaDiv);
        }
        
        mostrarHorariosDisponibles(responseData, requestData);
        
    } catch (error) {
        console.error('Error en la consulta:', error);
        resultadoDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error al consultar disponibilidad</strong><br>
                ${error.message}
            </div>
        `;
    }
});

// ===========================================
// MOSTRAR RESULTADOS
// ===========================================

function mostrarHorariosDisponibles(responseData, requestData) {
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    
    // La respuesta ahora viene como array, tomar el primer elemento
    const data = Array.isArray(responseData) ? responseData[0] : responseData;
    
    if (!data) {
        resultadoDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> No se recibieron datos válidos del servidor
            </div>
        `;
        return;
    }
    
    const colaboradoresTexto = data.colaboradores ? data.colaboradores.join(', ') : 'N/A';
    
    let html = `
        <div class="mb-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Respuesta para: ${colaboradoresTexto}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>Fecha:</strong></div>
                        <div class="col-sm-8">${formatearFechaMexicana(data.fecha)}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Modalidad:</strong></div>
                        <div class="col-sm-8">${data.modalidad.charAt(0).toUpperCase() + data.modalidad.slice(1)}${data.duracion ? ` (${data.duracion} min)` : ''}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Mensaje:</strong></div>
                        <div class="col-sm-8">${data.mensaje || 'Sin mensaje'}</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    if (!data.success) {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Respuesta del sistema:</strong><br>
                ${data.mensaje || 'No se pudo obtener la disponibilidad'}
            </div>
        `;
        resultadoDiv.innerHTML = html;
        return;
    }

    const horariosReales = data.disponibilidad || [];

    if (horariosReales.length === 0) {
        html += `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>No hay horarios disponibles</strong><br>
                ${data.mensaje || 'No se encontraron horarios libres para la fecha seleccionada.'}
            </div>
        `;
        resultadoDiv.innerHTML = html;
        return;
    }

    html += `
        <div class="mb-4">
            <div class="text-center mb-3">
                <h4 class="text-success">Horarios Verificados</h4>
                <p class="text-muted mb-0">Respuesta directa de Google Calendar</p>
            </div>
            <div class="horarios-grid">
    `;

    horariosReales.forEach(horario => {
        const horaInicio = horario.hora_inicio || '09:00';
        const horaFin = horario.hora_fin || '10:00';
        const disponible = horario.disponible !== false;
        const duracion = horario.duracion || data.duracion || 30;
        
        if (disponible) {
            html += `
                <div class="horario-slot horario-agendable">
                    <div class="horario-tiempo">${formatearRangoHoraMexicana(horaInicio, horaFin)}</div>
                    <div class="horario-estado">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Disponible confirmado (${duracion} min)
                    </div>
                    <button class="btn-agendar" onclick="abrirModalAgendamiento('${horaInicio}', '${horaFin}', '${data.colaboradores.join(',')}', '${data.fecha}', '${data.modalidad}', '${duracion}')">
                        <i class="fas fa-calendar-plus me-1"></i>Agendar Ahora
                    </button>
                </div>
            `;
        } else {
            html += `
                <div class="horario-slot ocupado">
                    <div class="horario-tiempo">${formatearRangoHoraMexicana(horaInicio, horaFin)}</div>
                    <div class="horario-estado">
                        <i class="fas fa-times-circle text-warning me-1"></i>
                        Ocupado
                    </div>
                </div>
            `;
        }
    });

    html += `</div></div>`;
    
    // Agregar información de debug si está disponible (OCULTO PARA PRESENTACIÓN)
    if (data.debug) {
        html += `
            <div class="mb-4" style="display: none;">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información de Debug</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>Calendarios consultados:</strong><br>
                                ${data.debug.calendarios ? data.debug.calendarios.join(', ') : 'N/A'}
                            </div>
                            <div class="col-sm-6">
                                <strong>Eventos encontrados:</strong><br>
                                ${data.debug.eventosTotal || 0} eventos
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <strong>Horario laboral:</strong><br>
                                ${data.debug.horarioLaboral || 'N/A'}
                            </div>
                            <div class="col-sm-6">
                                <strong>Duración de slots:</strong><br>
                                ${data.debug.duracionSlot || 'N/A'}
                            </div>
                        </div>
                        ${data.debug.slotsOcupados && data.debug.slotsOcupados.length > 0 ? `
                            <div class="mt-2">
                                <strong>Horarios ocupados:</strong><br>
                                <small class="text-muted">${data.debug.slotsOcupados.join(', ')}</small>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }
    
    resultadoDiv.innerHTML = html;
}

// ===========================================
// MODAL DE AGENDAMIENTO - SIMPLIFICADO
// ===========================================

function abrirModalAgendamiento(horaInicio, horaFin, colaboradores, fecha, modalidad, duracion) {
    console.log('Función abrirModalAgendamiento ejecutada con:', {
        horaInicio, horaFin, colaboradores, fecha, modalidad, duracion
    });
    
    // Verificar que el modal exista
    const modalElement = document.getElementById('agendarModal');
    if (!modalElement) {
        console.error('Error: Modal no encontrado en el DOM');
        alert('Error: Modal no encontrado. Por favor recarga la página.');
        return;
    }
    
    console.log('Modal encontrado:', modalElement);
    
    // Limpiar cuadros de debug anteriores del modal
    const debugAgendarAnterior = document.getElementById('debugAgendar');
    const debugRespuestaAgendarAnterior = document.getElementById('debugRespuestaAgendar');
    if (debugAgendarAnterior) debugAgendarAnterior.remove();
    if (debugRespuestaAgendarAnterior) debugRespuestaAgendarAnterior.remove();
    
    // Convertir string de colaboradores a array
    const colaboradoresArray = colaboradores.split(',').map(c => c.trim());
    
    console.log('Colaboradores convertidos:', colaboradoresArray);
    
    datosAgendamiento = {
        colaboradores: colaboradoresArray,
        fecha: fecha,
        horaInicio: horaInicio,
        horaFin: horaFin,
        modalidad: modalidad,
        duracion: duracion
    };
    
    // Verificar que todos los elementos del modal estén disponibles
    const elementosModal = {
        colaboradores: document.getElementById('modalColaboradores'),
        fecha: document.getElementById('modalFecha'),
        horario: document.getElementById('modalHorario'),
        modalidad: document.getElementById('modalModalidad'),
        titulo: document.getElementById('modalTitulo'),
        descripcion: document.getElementById('modalDescripcion'),
        email: document.getElementById('modalEmail'),
        nombre: document.getElementById('modalNombre')
    };
    
    // Verificar que todos los elementos existan
    for (const [key, element] of Object.entries(elementosModal)) {
        if (!element) {
            console.error(`Error: Elemento ${key} no encontrado en el modal`);
            alert(`Error: Elemento ${key} no encontrado. Por favor recarga la página.`);
            return;
        }
    }
    
    console.log('Todos los elementos del modal encontrados');
    
    // Actualizar modal
    elementosModal.colaboradores.innerHTML = colaboradoresArray.map(c => 
        `<span class="badge bg-primary me-1">${c}</span>`
    ).join('');
    elementosModal.fecha.textContent = formatearFechaMexicana(fecha);
    elementosModal.horario.textContent = formatearRangoHoraMexicana(horaInicio, horaFin);
    elementosModal.modalidad.textContent = `${modalidad.charAt(0).toUpperCase() + modalidad.slice(1)}${duracion ? ` (${duracion} min)` : ''}`;
    
    // Limpiar formulario
    elementosModal.titulo.value = '';
    elementosModal.descripcion.value = '';
    elementosModal.email.value = '';
    elementosModal.nombre.value = '';
    
    // Agregar validación en tiempo real al campo email
    elementosModal.email.addEventListener('input', function() {
        const email = this.value.trim();
        const emailErrorDiv = document.getElementById('emailError');
        
        if (email && EMAILS_VALIDOS.includes(email.toLowerCase())) {
            const validacion = validarEmailVsColaboradores(email, colaboradoresArray);
            if (!validacion.valido) {
                this.classList.add('is-invalid');
                emailErrorDiv.textContent = validacion.mensaje;
                emailErrorDiv.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                emailErrorDiv.style.display = 'none';
            }
        } else {
            this.classList.remove('is-invalid');
            emailErrorDiv.style.display = 'none';
        }
    });
    
    // Verificar si Bootstrap está disponible
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        console.log('Bootstrap disponible, usando Modal de Bootstrap');
        const modal = new bootstrap.Modal(document.getElementById('agendarModal'));
        modal.show();
        console.log('Modal de Bootstrap mostrado');
    } else {
        console.log('Bootstrap no disponible, usando fallback manual');
        // Fallback: mostrar modal manualmente
        document.getElementById('agendarModal').style.display = 'block';
        document.getElementById('agendarModal').classList.add('show');
        document.body.classList.add('modal-open');
        
        // Agregar backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modalBackdrop';
        document.body.appendChild(backdrop);
        console.log('Modal manual mostrado');
    }
    
    console.log('Modal abierto exitosamente');
}

// ===========================================
// FUNCIONES PARA CERRAR MODAL
// ===========================================

function cerrarModal() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('agendarModal'));
        if (modal) {
            modal.hide();
        }
    } else {
        // Cerrar modal manualmente
        document.getElementById('agendarModal').style.display = 'none';
        document.getElementById('agendarModal').classList.remove('show');
        document.body.classList.remove('modal-open');
        
        // Remover backdrop
        const backdrop = document.getElementById('modalBackdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

// Agregar event listeners para cerrar modal
document.addEventListener('DOMContentLoaded', function() {
    // Botón de cerrar del modal
    const closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', cerrarModal);
    });
    
    // Cerrar al hacer clic fuera del modal
    document.getElementById('agendarModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
    
    // Cerrar con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('agendarModal').classList.contains('show')) {
            cerrarModal();
        }
    });
});

// ===========================================
// VALIDACIÓN DE EMAIL VS COLABORADORES
// ===========================================

function validarEmailVsColaboradores(email, colaboradoresSeleccionados) {
    const emailLower = email.toLowerCase();
    
    // Verificar si el email está en el mapeo
    if (!EMAIL_TO_COLABORADOR[emailLower]) {
        return {
            valido: true, // Si no está en el mapeo, no hay conflicto
            mensaje: null
        };
    }
    
    // Obtener el nombre del colaborador del email
    const nombreColaborador = EMAIL_TO_COLABORADOR[emailLower];
    
    // Verificar si ese colaborador está seleccionado
    if (colaboradoresSeleccionados.includes(nombreColaborador)) {
        return {
            valido: false,
            mensaje: `No puedes agendar una cita contigo mismo. El email ${email} corresponde a ${nombreColaborador}, que está seleccionado en los colaboradores.`
        };
    }
    
    return {
        valido: true,
        mensaje: null
    };
}

// ===========================================
// CONFIRMACIÓN DE AGENDAMIENTO
// ===========================================

document.getElementById('confirmarAgendar').addEventListener('click', async function() {
    const titulo = document.getElementById('modalTitulo').value.trim();
    const descripcion = document.getElementById('modalDescripcion').value.trim();
    const email = document.getElementById('modalEmail').value.trim();
    const nombre = document.getElementById('modalNombre').value.trim();
    
    if (!titulo) {
        alert('Por favor ingresa un título para la cita');
        return;
    }
    
    if (!email || !EMAILS_VALIDOS.includes(email.toLowerCase())) {
        alert('Email no válido. Solo se permiten emails de colaboradores de Fiborti.');
        return;
    }
    
    // Validar que el email no coincida con colaboradores seleccionados
    const validacionEmail = validarEmailVsColaboradores(email, datosAgendamiento.colaboradores);
    if (!validacionEmail.valido) {
        alert(validacionEmail.mensaje);
        return;
    }
    
    const requestData = {
        action: 'agendar_cita_multiple',
        colaboradores: datosAgendamiento.colaboradores,
        fecha: datosAgendamiento.fecha,
        hora_inicio: datosAgendamiento.horaInicio,
        hora_fin: datosAgendamiento.horaFin,
        duracion: datosAgendamiento.duracion,
        titulo: titulo,
        descripcion: descripcion,
        email: email,
        asistente: nombre || email,
        modalidad: datosAgendamiento.modalidad,
        timestamp: new Date().toISOString(),
        source: 'wordpress_form'
    };
    
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<div class="loading-spinner me-2"></div> Agendando...';
    btn.disabled = true;
    
    // Mostrar JSON de agendamiento en el contenedor principal (OCULTO PARA PRESENTACIÓN)
    const resultadoDivPrincipal = document.getElementById('disponibilidadResultado');
    const debugAgendarDiv = document.createElement('div');
    debugAgendarDiv.id = 'debugAgendar';
    debugAgendarDiv.className = 'mb-4';
    debugAgendarDiv.style.display = 'none'; // OCULTO
    debugAgendarDiv.innerHTML = `
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-bug me-2"></i>Debug - Datos de Agendamiento</h6>
            </div>
            <div class="card-body">
                <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">${JSON.stringify(requestData, null, 2)}</pre>
            </div>
        </div>
    `;
    // Insertarlo después del debug de respuesta de disponibilidad si existe, si no, después del de enviados
    const debugRespuesta = document.getElementById('debugRespuesta');
    const debugEnviado = document.getElementById('debugEnviado');
    if (debugRespuesta && debugRespuesta.nextSibling) {
        debugRespuesta.parentNode.insertBefore(debugAgendarDiv, debugRespuesta.nextSibling);
    } else if (debugEnviado && debugEnviado.nextSibling) {
        debugEnviado.parentNode.insertBefore(debugAgendarDiv, debugEnviado.nextSibling);
    } else {
        resultadoDivPrincipal.parentNode.insertBefore(debugAgendarDiv, resultadoDivPrincipal.nextSibling);
    }
    
    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });
        
        const responseData = JSON.parse(await response.text());
        
        // Mostrar respuesta del webhook de agendamiento (OCULTO PARA PRESENTACIÓN)
        const debugRespuestaAgendarDiv = document.createElement('div');
        debugRespuestaAgendarDiv.id = 'debugRespuestaAgendar';
        debugRespuestaAgendarDiv.className = 'mb-4';
        debugRespuestaAgendarDiv.style.display = 'none'; // OCULTO
        debugRespuestaAgendarDiv.innerHTML = `
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-reply me-2"></i>Debug - Respuesta de Agendamiento</h6>
                </div>
                <div class="card-body">
                    <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">${JSON.stringify(responseData, null, 2)}</pre>
                </div>
            </div>
        `;
        const debugAgendarActual = document.getElementById('debugAgendar');
        if (debugAgendarActual && debugAgendarActual.nextSibling) {
            debugAgendarActual.parentNode.insertBefore(debugRespuestaAgendarDiv, debugAgendarActual.nextSibling);
        } else if (debugAgendarActual) {
            debugAgendarActual.parentNode.appendChild(debugRespuestaAgendarDiv);
        }
        
        if (responseData.success) {
            cerrarModal();
            
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Cita agendada exitosamente!</strong><br>
                <div class="mt-2">
                    • Colaboradores: ${datosAgendamiento.colaboradores.join(', ')}<br>
                    • Fecha: ${formatearFechaMexicana(datosAgendamiento.fecha)}<br>
                    • Horario: ${formatearRangoHoraMexicana(datosAgendamiento.horaInicio, datosAgendamiento.horaFin)}<br>
                    • Duración: ${datosAgendamiento.duracion || 'Estándar'} minutos
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const title = document.querySelector('h1.card-title');
            title.parentNode.insertBefore(alertDiv, title.nextSibling);
            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
        } else {
            throw new Error(responseData.mensaje || 'Error al agendar');
        }
        
    } catch (error) {
        alert('Error al agendar la cita: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

// ===========================================
// FUNCIONES AUXILIARES PARA UI
// ===========================================

function cambiarARemota() {
    document.getElementById('remota').checked = true;
    document.getElementById('presencial').checked = false;
    
    const duracionSection = document.getElementById('duracionSection');
    duracionSection.style.display = 'block';
    duracionSection.classList.add('visible');
    document.querySelectorAll('input[name="duracion"]').forEach(d => d.required = true);
    
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    resultadoDiv.innerHTML = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Modalidad cambiada a Remota</strong><br>
            <small>Selecciona la duración y haz clic en "Consultar Disponibilidad"</small>
        </div>
    `;
}

function sugerirFechasValidas(modalidad) {
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    const hoy = new Date();
    const fechasValidas = [];
    
    for (let i = 1; i <= 30 && fechasValidas.length < 10; i++) {
        const fecha = new Date(hoy);
        fecha.setDate(hoy.getDate() + i);
        const dayOfWeek = fecha.getDay();
        
        let esValida = false;
        if (modalidad === 'presencial' && [2, 3, 4, 5].includes(dayOfWeek)) {
            esValida = true;
        } else if (modalidad === 'remota' && [1, 2, 3, 4, 5].includes(dayOfWeek)) {
            esValida = true;
        }
        
        if (esValida) {
            fechasValidas.push({
                fecha: fecha.toISOString().split('T')[0],
                display: formatearFechaMexicana(fecha)
            });
        }
    }
    
    let html = `
        <div class="alert alert-info">
            <h6><i class="fas fa-calendar-alt me-2"></i>Próximas fechas válidas para reuniones ${modalidad}s:</h6>
            <div class="mt-3 text-center">
    `;
    
    fechasValidas.forEach((fechaInfo, index) => {
        const variant = index < 3 ? 'primary' : 'outline-primary';
        html += `
            <button class="btn btn-${variant} btn-sm me-2 mb-2" onclick="seleccionarFecha('${fechaInfo.fecha}')">
                <i class="fas fa-calendar-day me-1"></i>${fechaInfo.display}
            </button>
        `;
    });
    
    html += `</div></div>`;
    resultadoDiv.innerHTML = html;
}

function seleccionarFecha(fecha) {
    document.getElementById('fechaReunion').value = fecha;
    
    const resultadoDiv = document.getElementById('disponibilidadResultado');
    resultadoDiv.innerHTML = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Fecha seleccionada:</strong> ${formatearFechaMexicana(new Date(fecha))}<br>
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="document.getElementById('disponibilidadForm').dispatchEvent(new Event('submit'))">
                    <i class="fas fa-search me-1"></i>Consultar Disponibilidad Ahora
                </button>
            </div>
        </div>
    `;
}



console.log('Sistema de Calendario Fiborti cargado - Versión limpia');
