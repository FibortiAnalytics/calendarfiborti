// ===========================================
// SISTEMA DE CALENDARIO FIBORTI - CÓDIGO LIMPIO
// ===========================================

// URL del webhook de n8n
const WEBHOOK_URL = 'https://n8n.srv915832.hstgr.cloud/webhook-test/fiborti-availability-v2';

// Emails válidos para validación
const EMAILS_VALIDOS = [
    'agencia@fiborti.com',
    'danny.fiborti@gmail.com',
    'mauleon1119@gmail.com',
    'fernando.fiborti@gmail.com',
    'emilio.fiborti@gmail.com'
];

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
    const fechaObj = new Date(fecha);
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
    
    // Crear cuadro de debug permanente para datos enviados
    const debugEnviadoDiv = document.createElement('div');
    debugEnviadoDiv.id = 'debugEnviado';
    debugEnviadoDiv.className = 'mb-4';
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
        
        // Crear cuadro de debug permanente para respuesta del webhook
        const debugRespuestaDiv = document.createElement('div');
        debugRespuestaDiv.id = 'debugRespuesta';
        debugRespuestaDiv.className = 'mb-4';
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
    const colaboradoresTexto = requestData.colaboradores.join(', ');
    
    let html = `
        <div class="mb-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Respuesta para: ${colaboradoresTexto}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>Fecha:</strong></div>
                        <div class="col-sm-8">${formatearFechaMexicana(requestData.fecha)}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Modalidad:</strong></div>
                        <div class="col-sm-8">${requestData.modalidad.charAt(0).toUpperCase() + requestData.modalidad.slice(1)}${requestData.duracion ? ` (${requestData.duracion} min)` : ''}</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    if (!responseData.success) {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Respuesta del sistema:</strong><br>
                ${responseData.mensaje || 'No se pudo obtener la disponibilidad'}
            </div>
        `;
        resultadoDiv.innerHTML = html;
        return;
    }

    const horariosReales = responseData.disponibilidad || [];

    if (horariosReales.length === 0) {
        html += `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>No hay horarios disponibles</strong><br>
                ${responseData.mensaje || 'No se encontraron horarios libres para la fecha seleccionada.'}
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
        
        if (disponible) {
            html += `
                <div class="horario-slot horario-agendable">
                    <div class="horario-tiempo">${formatearRangoHoraMexicana(horaInicio, horaFin)}</div>
                    <div class="horario-estado">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Disponible confirmado
                    </div>
                    <button class="btn-agendar" onclick="abrirModalAgendamiento('${horaInicio}', '${horaFin}', '${requestData.colaboradores.join(',')}', '${requestData.fecha}', '${requestData.modalidad}', '${requestData.duracion || ''}')">
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
    
    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });
        
        const responseData = JSON.parse(await response.text());
        
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
