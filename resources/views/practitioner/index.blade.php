@extends('layout/base')

@section('content')
<div class="p-5">
    <h1>Liste des praticiens</h1>

    <a href="{{ route('practitioner.create') }}" type="button" class="btn btn-primary mt-4 mb-4">Ajouter un praticien</a>

    <table class="table">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Spécialité</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($practitioners as $practitioner)
                <tr>
                    <td>{{ $practitioner->firstname }}</td>
                    <td>{{ $practitioner->lastname }}</td>
                    <td>{{ $practitioner->speciality->name }}</td>
                    <td class="text-end">

                        <form action="{{ route('practitioner.destroy', ['id' => $practitioner->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route("practitioner.edit", ["id" => $practitioner->id]) }}" class="btn btn-warning m-1">éditer</a>

                            <button type="submit" class="btn btn-danger m-1">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
