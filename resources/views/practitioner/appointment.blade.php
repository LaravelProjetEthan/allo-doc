@extends('layout/base')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center h-100 mt-3">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="/img/medical-care.svg"
                class="img-fluid" alt="">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <h1>Prise d'un rendez-vous</h1>

            <!-- affichage des erreurs fournies par $request->validate() -->
            @if ($errors->any())
            <article class="alert alert-danger">
                <p>Problème dans le formulaire
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </p>
            </article>
            @endif

            <form action="{{ route('appointment', [
                'id_practitioner' => $practitioner->id,
                'date_appointment' => $date_appointment,
                'hour_appointment' => $hour_appointment
                ]) }}" method="POST">
                @csrf

                <div class="form-outline mb-2">
                    <label class="form-label" for="reason">Praticien :</label>
                    {{ $practitioner->firstname }} {{ $practitioner->lastname }}
                </div>

                <div class="form-outline mb-2">
                    <label class="form-label" for="reason">Jour :</label>
                    {{ $dateHelper->getFrenchDayFromString($date_appointment) }}
                </div>

                <div class="form-outline mb-2">
                    <label class="form-label" for="reason">Heure :</label>
                    {{ $hour_appointment }}h00
                </div>


                <div class="form-outline mb-2">
                    <label class="form-label" for="reason">Motif du rendez-vous</label>
                    <input type="text" id="reason" name="reason" class="form-control form-control-lg"
                        placeholder="motif" />
                </div>

                <div class="text-center text-lg-start">
                    <button type="submit" class="btn btn-primary btn-lg">Confirmer le rendez-vous</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
