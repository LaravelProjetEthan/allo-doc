@extends('layout/base')

@section('content')

    <div class="container-fluid p-3">
        <h1 class="fw-bold">Site de réservation de RDV médicaux</h1>

        <form action="{{ route('search-practitioner') }}" method="POST"
              class="row row-cols-auto row-cols-md-2 row-cols-lg-3 g-3">
            @csrf
            <div class="col-12 col-lg-4 col-xxl-3">
                <label class="visually-hidden" for="specialty">Spécialité du praticien recherché</label>
                <select name="speciality_id" id="specialty" class="form-select">
                    <option value="0">Spécialité du praticien recherché</option>
                    @foreach ($specialties as $specility)
                        <option value="{{ $specility->id }}">{{ $specility->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-lg-4 col-xxl-3">
                <label class="visually-hidden" for="city">Ville du praticien recherché</label>
                <select name="city" id="city" class="form-select">
                    <option value="0">Ville du praticien recherché</option>
                    @foreach ($cities as $city_select)
                        <option value="{{ $city_select->city }}">{{ $city_select->city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Rechercher</button>
            </div>
        </form>


        <div class="row mt-3">
            <div class="col">
                <h2>Fiche d'information de Pierre Louis</h2>

                <table class="table w-auto">
                    <tbody>
                    <tr>
                        <td><strong>Spécialité :</strong></td>
                        <td>{{ $practitioner->speciality->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Adresse :</strong></td>
                        <td>{{ $practitioner->address }}<br>
                            {{ $practitioner->zipcode }} {{ $practitioner->city }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tél. :</strong></td>
                        <td>{{ $practitioner->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email :</strong></td>
                        <td>{{ $practitioner->email }}</td>
                    </tr>
                    </tbody>
                </table>


                <div class="row justify-content-end mb-1">
                    <div class="col">Pour prendre un rendez-vous, veuillez choisir un horaire disponible</div>
                    <div class="col text-end">
                        @if ($date_previous_week)
                            <a href="{{ route('detail-practitioner', ["id" => $practitioner->id, "start_date" => $date_previous_week->format("Y-m-d")]) }}"
                               class="btn btn-primary">Semaine précédente</a>
                        @endif


                        <a href="{{ route('detail-practitioner', ["id" => $practitioner->id, "start_date" => $date_next_week->format("Y-m-d")]) }}"
                           class="btn btn-primary">Semaine suivante</a>
                    </div>
                </div>

                @include('practitioner/planning')
            </div>
        </div>
    </div>
@endsection
