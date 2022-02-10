<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Practitioner;
use App\Models\Appointment;

class PractitionerPlanning
{
    private static $appointment;

    /**
     * Récupère le planning de la semaine avec les créneaux contenant les rendez-vous du praticien
     *
     * @param Practitioner $practitioner
     * @param Carbon|null $startDay
     * @param integer $nbDays
     * @return array
     */
    public static function get(Practitioner $practitioner, Carbon $startDay = null, int $nbDays = 5): array
    {
        // indique à la lib Carbon de travailler en français
        Carbon::setLocale("fr");

        // définit à la date du jour
        $startDay = $startDay ?? Carbon::now();

        // tableau qui stockera le planning
        $planning = [];

        // gestion de la période via Carbon
        $periodDays = CarbonPeriod::create($startDay, 7);

        // charge les rendez-vous du praticien sur la période
        self::getPractitionerAppointments($practitioner, $periodDays);

        // lecture de l'ensemble des jours de la période
        foreach ($periodDays as $day) {
            if ($day->isWeekDay()) {
                // affecte pour chaque jour les créneaux horaires
                $planning[$day->format("Y-m-d")] = self::getSlots($day);

                // Transmet les infos sur le jour en français
                $planning[$day->format("Y-m-d")]["label"] = $day->translatedFormat("d F Y");
            }
        }

        // retourne le planning
        return $planning;
    }

    /**
     * Récupère les créneaux pour le jour passé en argument
     *
     * @param Carbon $day
     * @return array
     */
    private static function getSlots(Carbon $day): array
    {
        $slots = [];
        // définit la liste des créneaux de 9h à 18h + l'exclusion de 13h
        for ($hour = 9; $hour <= 18; $hour++) {
            // exclusion du créneau de 13h
            if ($hour != 13) {
                // recherche si le praticien à un rendez-vous sur le créneau
                $slots[$hour] = self::getAppointment($day, $hour);
            }
        }
        // retourne les créneaux du jour
        return $slots;
    }

    /**
     * Récupère la liste des rendez-vous sur la période pour le praticien
     * Les données sont stockés dans la variable static $appointment
     *
     * @param Practitioner $practitioner
     * @param CarbonPeriod $periodDays
     * @return void
     */
    private static function getPractitionerAppointments(Practitioner $practitioner, CarbonPeriod $periodDays)
    {
        // Réinitialise le tableau avec la liste des rendez-vous du praticien
        self::$appointment = [];
        // déterminé la date de début et fin de période
        $startDay = $periodDays->getStartDate()->format("Y-m-d 00:00:00");
        $endDay = $periodDays->getStartDate()->addDay($periodDays->getRecurrences() - 1)->format("Y-m-d 00:00:00");
        // récupère tous les rendez-vous du praticien sur la période donnée
        $appointments = Appointment::whereBetween("meet_at", [
            $startDay,
            $endDay,
        ])
            ->where("practitioner_id", $practitioner->id)
            ->where("status", "active")
            ->get();
        // stock tous les rendez-vous dans la variable static $appointment
        foreach ($appointments as $appointment) {
            $dateAppointment = new Carbon($appointment->meet_at);

            $day = $dateAppointment->format("Y-m-d");
            $hour = $dateAppointment->format("H") * 1;

            // creation de la date dans le tableau des RDV s'il n'existe pas
            self::$appointment[$day] = self::$appointment[$day] ?? [];

            // stock le rendez-vous sur le bon créneau
            self::$appointment[$day][$hour] = $appointment;
        }
    }


    /**
     * Récupère le rendez-vous pour le jour et l'heure ou renvoie une valeur null
     *
     * @param Carbon $day
     * @param integer $hour
     * @return Appointment|null
     */
    private static function getAppointment(Carbon $day, int $hour): ?Appointment
    {
        return self::$appointment[$day->format("Y-m-d")][$hour] ?? null;
    }
}
