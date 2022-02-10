@extends('layout/base')

@section('content')

<div class="p-5" id="search-form">

    <h1 class="fw-bold text-light">Résultat de la recherche

    @if ($speciality)
        pour "<span>{{ $speciality->name }}</span>"
    @else
        d'un praticien
    @endif

    @if ($city)
        à <span>{{ $city }}</span>
    @endif </h1>

    <form action="{{ route('search-practitioner') }}" method="POST" class="row row-cols-auto row-cols-md-2 row-cols-lg-3 g-3">
        @csrf
        <div class="col-12 col-lg-4 col-xxl-3">
            <label class="visually-hidden" for="specialty">Spécialité du praticien recherché</label>
            <select name="speciality_id" id="specialty" class="form-select">
                <option value="0" @if ($speciality_id == 0) selected @endif>Spécialité du praticien recherché</option>
                @foreach ($specialties as $specility)
                    <option value="{{ $specility->id }}" @if ($speciality_id == $specility->id)
                        selected
                    @endif>{{ $specility->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-lg-4 col-xxl-3">
            <label class="visually-hidden" for="city">Ville du praticien recherché</label>
            <select name="city" id="city" class="form-select">
                <option value="0" @if ($city == 0) selected @endif>Ville du praticien recherché</option>
                @foreach ($cities as $city_select)
                    <option value="{{ $city_select }}"@if ($city == $city_select)
                        selected
                    @endif>{{ $city_select }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-outline-light">Rechercher</button>
        </div>
    </form>
</div>

<div class="container-fluid p-3">
    @if ($practitioners->count())
        <div class="row justify-content-end mb-1">
            <div class="col text-end">
                @if ($date_previous_week)
                    <form action="{{ route('search-practitioner') }}" class="btnPreviousNextWeek" method="POST">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $date_previous_week }}" />
                        <input type="hidden" name="speciality_id" value="{{ $speciality_id }}" />
                        <input type="hidden" name="city" value="{{ $city }}" />
                        <button class="btn btn-primary">Semaine précédente</button>
                    </form>
                @endif

                <form action="{{ route('search-practitioner') }}" class="btnPreviousNextWeek" method="POST">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $date_next_week }}" />
                    <input type="hidden" name="speciality_id" value="{{ $speciality_id }}" />
                    <input type="hidden" name="city" value="{{ $city }}" />
                    <button class="btn btn-primary">Semaine suivante</button>
                </form>
            </div>
        </div>



        <section>
            @foreach ($practitioners as $practitioner)
                <article class="rounded d-flex flex-column flex-md-row p-3 bg-info bg-gradient mb-2">
                    <div class="data me-md-3">
                        <h3 class="fs-3"><a href="{{ route('detail-practitioner', ["id" => $practitioner->id]) }}" class="fw-bold text-light">Dr {{ $practitioner->firstname }} {{ $practitioner->lastname }}</a></h3>
                        <p class="fw-bold mb-0">{{ $practitioner->speciality->name }}</p>
                        <p>{{ $practitioner->address }} {{ $practitioner->zipcode }} {{ $practitioner->city }}</p>
                    </div>



                    @include('practitioner/planning')



                </article>
            @endforeach
        </section>
    @else
        <div class="row">
            <div class="col text-center"><img src="/img/doctor-1.svg" alt="..." style="max-width: 400px"></div>
            <div class="col fs-1">Désolé aucun praticien ne correspond à votre recherche</div>
        </div>
    @endif
</div>
@endsection
