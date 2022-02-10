@extends('layout/base')

@section('content')
<div class="container-fluid my-3">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="img/authentication.svg" class="img-fluid" alt="">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <h1>Inscription</h1>

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

            <form action="{{ route('signupPost') }}" method="POST">
                @csrf
                <div class="form-outline mb-2">
                    <label class="form-label" for="firstname">Prénom</label>
                    <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" class="form-control form-control-lg"
                        placeholder="Prénom" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="lastname">Nom</label>
                    <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" class="form-control form-control-lg" placeholder="Nom" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="address">Rue</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" class="form-control form-control-lg" placeholder="Rue" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="zipcode">Code Postal</label>
                    <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode') }}" class="form-control form-control-lg"
                        placeholder="Code Postal" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="city">Ville</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" class="form-control form-control-lg" placeholder="Ville" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Email" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control form-control-lg"
                        placeholder="Mot de passe" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="passwordConfirm">Confirmer le mot de passe</label>
                    <input type="password" id="passwordConfirm" name="passwordConfirm" value="{{ old('passwordConfirm') }}" class="form-control form-control-lg"
                        placeholder="Confirmer le mot de passe" />
                </div>
                <div class="text-center text-lg-start">
                    <button type="submit" class="btn btn-primary btn-lg">Inscription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
