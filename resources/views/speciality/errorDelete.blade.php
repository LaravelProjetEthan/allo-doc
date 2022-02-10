@extends('layout/base')

@section('content')
<div class="p-5">
    <h1>Suppression d'une spécialité</h1>

    <div class="alert alert-danger">
        Cette spécialité ne peut-être supprimée car elle est définie pour un praticien
        <a href="{{ route("speciality.index") }}">Retour</a>

    </div>
</div>
@endsection
