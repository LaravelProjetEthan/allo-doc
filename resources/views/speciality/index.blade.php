@extends('layout/base')

@section('content')
<div class="p-5">
    <h1>Liste des spécialités</h1>

    <a href="{{ route('speciality.create') }}" type="button" class="btn btn-primary mt-4 mb-4">Ajouter une spécialité</a>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($specialties as $speciality)
                <tr>
                    <td>{{ $speciality->name }}</td>
                    <td class="text-end">

                        <form action="{{ route('speciality.destroy', ['id' => $speciality->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route("speciality.edit", ["id" => $speciality->id]) }}" class="btn btn-warning m-1">éditer</a>

                            <button type="submit" class="btn btn-danger m-1">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
