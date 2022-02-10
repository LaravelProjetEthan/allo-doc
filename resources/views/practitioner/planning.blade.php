<div class="appointments d-md-flex w-100">

    @foreach ($practitioner->slots as $day => $slotDay)
    <div class="day mb-2 me-3">
        <time datetime="{{ $day }}">{{ $slotDay["label"] }}</time>

        @foreach ($slotDay as $hour => $appointments)
            @if (is_numeric($hour))
                @if ($appointments)
                    <button class="btn btn-secondary mb-2 appointment" disabled>{{ $hour }}:00</button>
                @else
                    <a href="{{ route('appointment', [
                        'id_practitioner' => $practitioner->id,
                        'date_appointment' => $day,
                        'hour_appointment' => $hour
                        ]) }}" class="btn btn-primary mb-2 appointment">{{ $hour }}:00</a>
                @endif
            @endif
        @endforeach
    </div>
    @endforeach
</div>
