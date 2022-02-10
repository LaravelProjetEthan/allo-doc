@extends('layout/base')


@section('content')


<div class="container">
    <h1 class="mb-3">Mes rendez-vous</h1>

    @if ($upcomingAppointments->count())
        <div class="row mb-4">
            <h2>Rendez-vous à venir</h2>

            @foreach ($upcomingAppointments as $appointment)
                <div class="col-sm-6 col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rendez-vous avec Dr {{ $appointment->practitioner->firstname }} {{ $appointment->practitioner->lastname }}</h5>
                            <p class="card-text">
                                <span class="badge bg-primary">{{ $appointment->practitioner->speciality->name }}</span>
                                <ul class="list-unstyled">
                                    <li>{{ $dateHelper->getFrenchDayFromString($appointment->meet_at) }}</li>
                                    <li>{{ $dateHelper->getCarbonDate($appointment->meet_at)->format("H") }}h00</li>
                                    <li>{{ $appointment->practitioner->address }} {{ $appointment->practitioner->zipcode }} {{ $appointment->practitioner->city }}</li>
                                </ul>
                            </p>
                            <form action="">
                                <button class="btn btn-warning btn-cancel-appointment" type="button" data-bs-toggle="offcanvas" data-id="{{ $appointment->id }}" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Annuler le rendez-vous</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    @if ($previousAppointments->count())
        <div class="row">
            <h2>Rendez-vous passés</h2>

            @foreach ($previousAppointments as $appointment)
                <div class="col-sm-6 col-md-4 text-black-50 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rendez-vous avec Dr {{ $appointment->practitioner->firstname }} {{ $appointment->practitioner->lastname }}</h5>
                            <p class="card-text">
                                <span class="badge bg-secondary">{{ $appointment->practitioner->speciality->name }}</span>
                                <ul class="list-unstyled">
                                    <li>{{ $dateHelper->getFrenchDayFromString($appointment->meet_at) }}</li>
                                    <li>{{ $dateHelper->getCarbonDate($appointment->meet_at)->format("H") }}h00</li>
                                    <li>{{ $appointment->practitioner->address }} {{ $appointment->practitioner->zipcode }} {{ $appointment->practitioner->city }}</li>
                                </ul>
                            </p>
                        </div>
                    </div>
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
        document.querySelector('#formDeleteAppointment').action = '/my-appointment/patient/delete/' + idAppointment;
    });
});
</script>

@endsection
