// Obtener datos 
const requestData = $node["Code extraer y validar"].json;
const allInputs = $input.all();

const duracion = requestData.duracion || 30;
const fecha = requestData.fecha;
const modalidad = requestData.modalidad.toLowerCase();
const colaboradoresSeleccionados = requestData.colaboradores;

console.log('=== INICIO DEBUG ===');
console.log('Procesando calendarios para:', colaboradoresSeleccionados);
console.log('Modalidad:', modalidad, 'Duración:', duracion);
console.log('Total inputs recibidos:', allInputs.length);

// Configurar horarios según modalidad
let inicioLaboral, finLaboral;
if (modalidad === 'presencial') {
  inicioLaboral = 9;    // 9:00 AM (expandido)
  finLaboral = 18;      // 6:00 PM (expandido)
} else {
  inicioLaboral = 8;    // 8:00 AM (expandido)
  finLaboral = 19;      // 7:00 PM (expandido)
}

// Generar todos los slots posibles según duración + tiempo de buffer
const intervalos = duracion / 60; // Convertir minutos a horas decimales

// Definir tiempo de buffer según modalidad
let tiempoBuffer = 0;
if (modalidad === 'presencial') {
  tiempoBuffer = 30; // 30 minutos para transporte
} else {
  tiempoBuffer = 15; // 15 minutos para descompresión
}

const intervalosConBuffer = (duracion + tiempoBuffer) / 60; // Incluir buffer en el cálculo
const todosLosSlots = [];

for (let hora = inicioLaboral; hora <= finLaboral - intervalosConBuffer; hora += 0.5) {
  const horaEntera = Math.floor(hora);
  const minutos = (hora % 1) * 60;
  const horaFin = hora + intervalosConBuffer;
  const horaFinEntera = Math.floor(horaFin);
  const minutosFin = (horaFin % 1) * 60;
  
  todosLosSlots.push({
    horaDecimal: hora,
    inicio: `${String(horaEntera).padStart(2,'0')}:${String(minutos).padStart(2,'0')}`,
    fin: `${String(horaFinEntera).padStart(2,'0')}:${String(minutosFin).padStart(2,'0')}`,
    duracionReal: duracion,
    tiempoBuffer: tiempoBuffer,
    duracionTotal: duracion + tiempoBuffer
  });
}

console.log(`Slots generados: ${todosLosSlots.length}`);

// CORREGIR: Procesar eventos de TODOS los calendarios
const todosLosEventos = [];

// Procesar cada input (cada calendario)
allInputs.forEach((inputItem, index) => {
  console.log(`--- Procesando input ${index + 1} ---`);
  
  // Verificar si el input tiene datos
  if (inputItem && inputItem.json) {
    // Si es un array de eventos
    if (Array.isArray(inputItem.json)) {
      console.log(`Input ${index + 1} - Array con ${inputItem.json.length} eventos`);
      inputItem.json.forEach(event => {
        if (event.start && event.end) {
          todosLosEventos.push(event);
          console.log(`- Evento: ${event.summary || 'Sin título'} (${event.start.dateTime} - ${event.end.dateTime})`);
        }
      });
    }
    // Si es un evento único
    else if (inputItem.json.start && inputItem.json.end) {
      console.log(`Input ${index + 1} - Evento único`);
      todosLosEventos.push(inputItem.json);
      console.log(`- Evento: ${inputItem.json.summary || 'Sin título'}`);
    }
  } else {
    console.log(`Input ${index + 1} - Sin datos o formato incorrecto`);
  }
});

console.log(`=== EVENTOS COMBINADOS: ${todosLosEventos.length} ===`);

// Encontrar slots ocupados por cualquier evento
const slotsOcupados = new Set();

todosLosEventos.forEach((event, index) => {
  if (!event.start?.dateTime || !event.end?.dateTime) {
    console.log(`Evento ${index + 1} sin horarios válidos`);
    return;
  }
  
  // Parsear horarios del evento
  const startTime = event.start.dateTime;
  const endTime = event.end.dateTime;
  
  const startHour = parseInt(startTime.substring(11, 13));
  const startMin = parseInt(startTime.substring(14, 16));
  const endHour = parseInt(endTime.substring(11, 13));
  const endMin = parseInt(endTime.substring(14, 16));
  
  const eventoInicio = startHour + (startMin / 60);
  const eventoFin = endHour + (endMin / 60);
  
// Marcar slots que se solapan con este evento (incluyendo buffer)
let slotsAfectados = 0;
todosLosSlots.forEach(slot => {
  const slotFin = slot.horaDecimal + intervalosConBuffer;
  
  // Si hay solapamiento, marcar como ocupado
  if (slot.horaDecimal < eventoFin && slotFin > eventoInicio) {
    slotsOcupados.add(slot.inicio);
    slotsAfectados++;
  }
});
  
  console.log(`  -> Afectó ${slotsAfectados} slots`);
});

// Filtrar solo slots disponibles (libres en TODOS los calendarios)
const slotsDisponibles = todosLosSlots.filter(slot => {
  return !slotsOcupados.has(slot.inicio);
});

console.log(`=== RESULTADO FINAL ===`);
console.log(`Slots ocupados: ${slotsOcupados.size}`);
console.log(`Slots disponibles: ${slotsDisponibles.length}`);
console.log('Slots ocupados detalle:', Array.from(slotsOcupados));

// Respuesta estructurada para el frontend
return [{
  success: true,
  colaboradores: colaboradoresSeleccionados,
  fecha: fecha,
  modalidad: modalidad,
  duracion: duracion,
  disponibilidad: slotsDisponibles.map(slot => {
    // Calcular hora de fin REAL (sin buffer)
    const horaFinReal = slot.horaDecimal + intervalos;
    const horaFinRealEntera = Math.floor(horaFinReal);
    const minutosFinReal = (horaFinReal % 1) * 60;
    const horaFinRealFormateada = `${String(horaFinRealEntera).padStart(2,'0')}:${String(minutosFinReal).padStart(2,'0')}`;
    
    return {
      hora_inicio: slot.inicio,
      hora_fin: horaFinRealFormateada,  // ← SOLO duración real
      disponible: true,
      duracion: duracion,
      tiempoBuffer: slot.tiempoBuffer,
      duracionTotal: slot.duracionTotal,
      tipoBuffer: modalidad === 'presencial' ? 'transporte' : 'descompresión'
    };
  }),
  mensaje: slotsDisponibles.length > 0 
    ? `Encontrados ${slotsDisponibles.length} horarios donde TODOS los colaboradores están disponibles`
    : 'No se encontraron horarios donde todos los colaboradores estén disponibles simultáneamente',
    debug: {
        calendarios: colaboradoresSeleccionados,
        inputsRecibidos: allInputs.length,
        eventosTotal: todosLosEventos.length,
        slotsOcupados: Array.from(slotsOcupados),
        horarioLaboral: `${String(Math.floor(inicioLaboral)).padStart(2,'0')}:00 - ${String(Math.floor(finLaboral)).padStart(2,'0')}:${String((finLaboral % 1) * 60).padStart(2,'0')}`,
        duracionSlot: `${duracion} minutos`,
        tiempoBuffer: `${tiempoBuffer} minutos (${modalidad === 'presencial' ? 'transporte' : 'descompresión'})`,
        duracionTotal: `${duracion + tiempoBuffer} minutos`,
        modalidad: modalidad,
        eventosDetalle: todosLosEventos.map(e => ({
          titulo: e.summary || 'Sin título',
          inicio: e.start?.dateTime,
          fin: e.end?.dateTime
        }))
      }
}];