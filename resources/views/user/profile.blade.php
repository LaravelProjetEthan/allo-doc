@extends('layout/base')

@section('content')
<div class="container my-3">
    <div class="row d-flex justify-content-center align-items-center h-100">

        <div class="col-md-12">
            <h1>Mon profil</h1>

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

            <form action="{{ route('profilePost') }}" method="POST">
                @csrf

                <div class="row">

                    @if (Auth::user()->role == 'patient' || Auth::user()->role == 'practitioner')
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="firstname">Prénom</label>
                                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname', Auth::user()->patient->firstname ?? Auth::user()->practitioner->firstname) }}" class="form-control form-control-lg"
                                        placeholder="Prénom" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="lastname">Nom</label>
                                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname', Auth::user()->patient->lastname ?? Auth::user()->practitioner->lastname) }}" class="form-control form-control-lg" placeholder="Nom" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-2">
                                <label class="form-label" for="address">Rue</label>
                                <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->patient->address ?? Auth::user()->practitioner->address) }}" class="form-control form-control-lg" placeholder="Rue" />
                            </div>


                            <div class="row">
                                <div class="col-4">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="zipcode">Code Postal</label>
                                        <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', Auth::user()->patient->zipcode ?? Auth::user()->practitioner->zipcode) }}" class="form-control form-control-lg"
                                        placeholder="Code Postal" />
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="city">Ville</label>
                                        <input type="text" id="city" name="city" value="{{ old('city', Auth::user()->patient->city ?? Auth::user()->practitioner->city) }}" class="form-control form-control-lg" placeholder="Ville" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col">
                        <div class="form-outline mb-2">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control form-control-lg" placeholder="Email" />
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
                    </div>
                </div>

                <div class="text-center text-lg-start">
                    <button type="submit" class="btn btn-primary btn-lg">Enregistrer</button>
                </div>
            </form>




            <fieldset>
                <legend>Zone dangereuse</legend>

                @if (Auth::user()->role == 'patient' || Auth::user()->role == 'practitioner')
                    <div>
                        Si vous souhaitez supprimer définitivement votre compte utilisateur, cliquez sur le bouton "Supprimer mon compte". ATTENTION : cette suppression est définitive et tous les rendez-vous que vous avez pris seront supprimés
                    </div>
                @endif

                <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Supprimer mon compte</button>
            </fieldset>


            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-body text-center">
                    <form action="{{ route('profileDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <p class="fs-2">Merci de confirmer la suppression de votre compte</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Annuler</button>
                        <button type="submit" class="btn btn-danger">Je confirme</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
