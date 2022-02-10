@extends('layout/base')

@section('content')
<div class="p-5">
    <h1>Liste des patients</h1>

    <a href="{{ route('patient.create') }}" type="button" class="btn btn-primary mt-4 mb-4">Ajouter un patient</a>

    <table class="table">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->firstname }}</td>
                    <td>{{ $patient->lastname }}</td>
                    <td>{{ $patient->email }}</td>
                    <td class="text-end">

                        <form action="{{ route('patient.destroy', ['id' => $patient->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route("patient.edit", ["id" => $patient->id]) }}" class="btn btn-warning m-1">éditer</a>

                            <button type="submit" class="btn btn-danger m-1">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
