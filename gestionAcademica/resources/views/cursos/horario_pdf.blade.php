<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario de {{ $curso->nombre }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header h3 { margin: 0; font-weight: normal; }
        .schedule-table { width: 100%; border-collapse: collapse; }
        .schedule-table th, .schedule-table td { border: 1px solid #ccc; padding: 6px; text-align: center; vertical-align: middle; }
        .schedule-table thead { background-color: #e9ecef; }
        .time-slot { font-weight: bold; }
        .class-cell { background-color: #f8f9fa; }
        .class-cell strong { font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Horario del Curso: {{ $curso->nombre }}</h1>
        <h3>{{ $curso->modulo->planEstudio->nombre ?? 'N/A' }} - Año Lectivo: {{ $curso->ano_lectivo }}</h3>
    </div>

    @php
        // 1. Definimos la estructura del horario (días y franjas horarias)
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $horas = [];
        if ($curso->turno == 'Mañana') {
            $horas = ['08:00', '08:40', '09:20', '10:00', '10:40', '11:20', '12:00'];
        } elseif ($curso->turno == 'Noche') {
            $horas = ['18:00', '18:40', '19:20', '20:00', '20:40', '21:20'];
        } else { // Tarde por defecto
            $horas = ['13:20', '14:00', '14:40', '15:20', '16:00', '16:40', '17:20'];
        }

        // 2. Pre-procesamos el horario para calcular rowspan y celdas ocupadas
        $scheduleGrid = [];
        foreach ($curso->horario as $item) {
            $start = \Carbon\Carbon::parse($item->hora_inicio);
            $end = \Carbon\Carbon::parse($item->hora_fin);
            // Asumiendo slots de 40 min, calculamos cuántas filas ocupará
            $durationInSlots = round($start->diffInMinutes($end) / 40);
            
            $scheduleGrid[$item->dia_semana][$start->format('H:i')] = [
                'materia' => $item->materia,
                'docente' => $item->docente,
                'rowspan' => $durationInSlots
            ];

            // Marcamos las celdas que serán ocupadas por el rowspan
            for ($i = 1; $i < $durationInSlots; $i++) {
                $nextSlotTime = $start->addMinutes(40)->format('H:i');
                $scheduleGrid[$item->dia_semana][$nextSlotTime] = 'occupied';
            }
        }
    @endphp

    <table class="schedule-table">
        <thead>
            <tr>
                <th style="width: 12%;">Hora</th>
                @foreach ($dias as $dia)
                    <th>{{ $dia }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($horas as $hora)
                <tr>
                    <td class="time-slot">{{ $hora }}</td>
                    @foreach ($dias as $dia)
                        @php
                            $cellData = $scheduleGrid[$dia][$hora] ?? null;
                        @endphp

                        @if ($cellData === 'occupied')
                            {{-- No renderizar esta celda, ya que está ocupada por un rowspan de una celda anterior --}}
                        @elseif (is_array($cellData))
                            {{-- Esta es la celda de inicio de una clase, la dibujamos con su rowspan --}}
                            <td rowspan="{{ $cellData['rowspan'] }}" class="class-cell">
                                <strong>{{ $cellData['materia']->nombre ?? 'N/A' }}</strong><br>
                                <small>{{ $cellData['docente']->apellido ?? 'N/A' }}</small>
                            </td>
                        @else
                            {{-- Esta es una celda vacía --}}
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>