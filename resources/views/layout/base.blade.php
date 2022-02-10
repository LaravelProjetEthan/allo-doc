<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/favicon_io/favicon.ico') }}" type="image/x-icon"/>
    <title>AllO'Doc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="d-flex flex-column h-100">
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand p-2" href="{{ route('home') }}">AllO'Doc</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!-- <a class="nav-link active" aria-current="page" href="#">Accueil</a> -->
                    </li>
                </ul>
                <div class="d-flex">
                    @if (\Illuminate\Support\Facades\Auth::user())
                        <div class="pt-3">
                            <a class="nav-link text-white" href="{{ route('profile') }}">
                                Mon profil ({{ \Illuminate\Support\Facades\Auth::user()->name }})
                            </a>
                        </div>

                        @if (\Illuminate\Support\Facades\Auth::user()->role == 'patient')
                            <a class="btn btn-outline-light m-2" href="{{ route('my-appointment-patient') }}">
                                Mes rendez-vous
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Auth::user()->role == 'practitioner')
                            <a class="btn btn-outline-light m-2" href="{{ route('my-appointment-practitioner') }}">
                                Mes rendez-vous
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Auth::user()->role == 'administrator')
                            <a class="btn btn-outline-light m-2" href="{{ route('speciality.index') }}">Spécialités</a>
                            <a class="btn btn-outline-light m-2" href="{{ route('practitioner.index') }}">Praticiens</a>
                            <a class="btn btn-outline-light m-2" href="{{ route('patient.index') }}">Patients</a>
                        @endif

                        <a class="btn btn-info m-2" href="{{ route('logout') }}">Se déconnecter</a>
                    @else
                        <a class="btn btn-outline-light m-2" href="{{ route('login') }}">Connexion</a>
                        <a class="btn btn-info m-2" href="{{ route('signup') }}">Inscription</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>
<main class="flex-shrink-0 mb-auto">
    <div class="container-fluid">
        @include('._partials/flash_message/flash')
        @yield('content')
    </div>
</main>
<footer>
    <div class="text-center p-4 bg-primary text-light">
        &copy; {{ now()->year }} Copyright AllO'Doc
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
        crossorigin="anonymous"></script>
</body>

</html>
