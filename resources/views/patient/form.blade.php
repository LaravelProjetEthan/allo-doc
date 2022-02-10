

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




<form action="{{ route('patient.store') }}" method="POST">
    <!-- Insertion du token CSRF géré par Laravel -->
    @csrf

    <!-- on stock l'ID du patient (vide si on est sur le formulaire en création) -->
    <input type="hidden" name="id" value="{{ $patient->id }}">

    <div class="field">
        <label class="form-label">Prénom</label>
        <div class="control">
            <input class="form-control" type="text" name="firstname" value="{{ old('firstname', $patient->firstname) }}">
        </div>
    </div>





    <div class="field">
        <label class="form-label">Nom</label>
        <div class="control">
            <input class="form-control" type="text" name="lastname" value="{{ old('lastname', $patient->lastname) }}">
        </div>
    </div>



    <div class="field">
        <label class="form-label">Email</label>
        <div class="control">
            <input class="form-control" type="email" name="email" value="{{ old('email', $patient->email) }}">
        </div>
    </div>


    <div class="field">
        <label class="form-label">Mot de passe</label>
        <div class="control">
            <input class="form-control" type="password" name="password" value="{{ old('password') }}">
        </div>
    </div>



    <div class="field">
        <label class="form-label">Adresse</label>
        <div class="control">
            <input class="form-control" type="text" name="address" value="{{ old('address', $patient->address) }}">
        </div>
    </div>


    <div class="field">
        <label class="form-label">Code postal</label>
        <div class="control">
            <input class="form-control" type="text" name="zipcode" value="{{ old('zipcode', $patient->zipcode) }}">
        </div>
    </div>


    <div class="field">
        <label class="form-label">Ville</label>
        <div class="control">
            <input class="form-control" type="text" name="city" value="{{ old('city', $patient->city) }}">
        </div>
    </div>


    <div class="m-4 text-center">
        <a href="{{ route('patient.index') }}" class="btn btn-secondary">Annuler</a>
        <button class="btn btn-primary">Enregistrer</button>
    </div>

</form>
