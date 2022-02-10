@extends('layout/base')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="/img/authentication.svg"
                class="img-fluid" alt="">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <h1>Connexion</h1>

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

            <form action="{{ route('loginPost') }}" method="POST">
                @csrf
                <div class="form-outline mb-2">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg"
                        placeholder="Email" />
                </div>
                <div class="form-outline mb-2">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control form-control-lg"
                        placeholder="Mot de passe" />
                </div>
                <div class="text-center text-lg-start">
                    <button type="submit" class="btn btn-primary btn-lg">Connexion</button>
                    <p class="small fw-bold mt-2 pt-1 mb-0">Pas encore de compte ? <a href="{{ route('signup') }}"
                            class="link-primary">Inscrivez-vous</a></p>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
