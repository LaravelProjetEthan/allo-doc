<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification d'annulation de votre rendez-vous</title>
</head>
<body>
    Bonjour,<br>
    <br>
    votre rendez-vous a été annulé.<br><br>
    Date : {{ $dateHelper->getFrenchDayFromString($appointment->meet_at) }}<br>
    Heure : {{ $dateHelper->getCarbonDate($appointment->meet_at)->format("H") }}h00<br>
    Praticien : Dr {{ $appointment->practitioner->firstname }} {{ $appointment->practitioner->lastname }}<br>
    Patient : {{ $appointment->patient->firstname }} {{ $appointment->patient->lastname }} <br>
    Motif : {{ $appointment->reason }}
</body>
</html>
