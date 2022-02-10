@extends('layout/base')

@section('content')
<div class="container-fluid my-3">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="/img/authentication.svg" class="img-fluid" alt="">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <h1>Inscription</h1>

            <p>Votre inscription est maintenant terminé, <a href="{{ route('login') }}">vous pouvez vous connecter</a></p>
        </div>
    </div>
</div>
@endsection
