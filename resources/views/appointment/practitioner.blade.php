@extends('layout/base')


@section('content')


<div class="container">
    <h1 class="mb-3">Mes rendez-vous</h1>

    @if (count($upcommingAppointments))
        <div class="row mb-4">
            <h2>Rendez-vous à venir</h2>

            @foreach ($upcommingAppointments as $day => $appointments)
                <div class="mt-3">
                    <h3 class="fw-bold">{{ $dateHelper->getFrenchDayFromString($day) }}</h3>
                    @foreach ($appointments as $appointment)
                        <div class="col-sm-12 col-md-12 mb-2">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-2 fs-1">{{ $dateHelper->getCarbonDate($appointment->meet_at)->format("H") }}h</div>
                                        <div class="col">
                                            <h5 class="card-title fw-bold">{{ $appointment->patient->firstname }} {{ $appointment->patient->lastname }}</h5>
                                            <p class="card-text">
                                                {{ $appointment->reason }}
                                            </p>
                                        </div>
                                        <div class="col text-end">
                                            <form action="">
                                                <button class="btn btn-warning btn-cancel-appointment" type="button" data-bs-toggle="offcanvas" data-id="{{ $appointment->id }}" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Annuler le rendez-vous</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center">
        <form action="" method="POST" id="formDeleteAppointment">
            @csrf
            @method('DELETE')
            <p class="fs-2">Merci de confirmer l'annulation du rendez-vous</p>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Annuler</button>
            <button type="submit" class="btn btn-danger">Je confirme</button>
        </form>
    </div>
</div>


<script>
// boucle pour selectionner tous les boutons d'annulation d'un rendez-vous
document.querySelectorAll(".btn-cancel-appointment").forEach(item => {
    // mise en place d'un écouteur d'événement pour chaque bouton
    item.addEventListener("click", function(event) {
        // récupération de l'ID du rendez-vous qui a été stocké
        // dans un attribut data-id sur le tag button
        let idAppointment = event.target.dataset.id;

        // change l'url de soumission du formulaire de suppression
        document.querySelector('#formDeleteAppointment').action = '/my-appointment/practitioner/delete/' + idAppointment;
    });
});
</script>

@endsection
