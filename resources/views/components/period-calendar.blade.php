@php
    $selectedDays = $selectedDays ?? [];
@endphp

<div class="calendar-legend">
    <span><i class="calendar-legend-box"></i> Disponible</span>
    <span><i class="calendar-legend-box is-used"></i> Usado</span>
    <span><i class="calendar-legend-box is-blocked"></i> Bloqueado</span>
    @if(! empty($selectedDays))
        <span><i class="calendar-legend-box is-selected"></i> Seleccionado</span>
    @endif
</div>

<div class="table-responsive">
    <table class="table table-bordered calendar-request-table mb-0">
        <thead>
        <tr>
            <th class="month-column">Mes</th>
            @for($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach($almanaque as $item)
            <tr>
                <th class="month-column">{{ $item[1] }}</th>
                @for($i = 1; $i <= 31; $i++)
                    @php
                        $date = $item[0] . "-" . str_pad($i, 2, 0, STR_PAD_LEFT);
                        $isBlocked = isset($domingos[$date]);
                        $isUsed = isset($marcar[$date]);
                        $isSelected = in_array($date, $selectedDays, true);
                    @endphp
                    <td class="calendar-day @if($isBlocked) is-blocked @endif @if($isUsed) is-used @endif @if($isSelected) is-selected @endif">
                        @if($isBlocked)
                            <i class="far fa-times"></i>
                        @elseif($isUsed)
                            <i class="far fa-check"></i>
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
