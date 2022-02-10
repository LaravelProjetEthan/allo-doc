<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation de votre adresse email</title>
</head>
<body>
    Bonjour,<br>
    <br>
    Pour valider votre adresse email, <a href="{{ route('validEmail', [
        'idUser' => $user->id,
        'token' => $user->remember_token
    ]) }}">merci de cliquer sur ce lien</a><br>
    <br>
    AllO'Doc

</body>
</html>
