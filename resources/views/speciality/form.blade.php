

<!-- affichage des erreurs fournies par $request->validate() -->
@if ($errors->any())
    <article class="alert alert-danger">
        <p>Problème dans le formulaire</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </article>
@endif




<form action="{{ route('speciality.store') }}" method="POST">
    <!-- Insertion du token CSRF géré par Laravel -->
    @csrf

    <!-- on stock l'ID de la spécialité (vide si on est sur le formulaire en création) -->
    <input type="hidden" name="id" value="{{ $speciality->id }}">

    <div class="field">
        <label class="form-label">Nom</label>
        <div class="control">
            <input class="form-control" type="text" name="name" value="{{ old('name', $speciality->name) }}">
        </div>
    </div>

    <div class="m-4 text-center">
        <a href="{{ route('speciality.index') }}" class="btn btn-secondary">Annuler</a>
        <button class="btn btn-primary">Enregistrer</button>
    </div>

</form>
