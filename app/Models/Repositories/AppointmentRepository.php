<?php

namespace App\Models\Repositories;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\User;
use App\Mail\AppointmentNotification;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository
{
    /**
     * Annulation d'un rendez-vous
     *
     * @param Appointment $model
     * @param boolean $sendEmail
     * @param array $notify
     * @return void
     */
    public static function delete(
        Appointment $model,
        bool        $sendEmail = true, array $notify = [
        'patient',
        'practitioner',
    ])
    {
        // En option envoi d'un mail lors de l'annulation des rendez-vous
        if ($sendEmail) {
            self::sendCanceledNotification($model, $notify);
        }
        // marque le rendez-vous comme étant annulé
        $model->delete();
    }

    /**
     * Envoi le mail de notification
     *
     * @param Appointment $model
     * @param array $notify
     * @return void
     */
    private static function sendCanceledNotification(
        Appointment $model,
        array       $notify = [
            'patient',
            'practitioner',
        ])
    {
        // envoi à mail à chaque personne concernée par la notification
        foreach ($notify as $person) {
            if ($person == 'patient') {
                Mail::to($model->patient->email)
                    ->send(new AppointmentNotification($model));
            } else {
                if ($person == 'practitioner') {
                    Mail::to($model->practitioner->email)
                        ->send(new AppointmentNotification($model));
                }
            }
        }
    }

    /**
     * Annule tous les rendez-vous d'un utilisateur
     *
     * @param User $user
     * @return void
     */
    public static function removeUser(User $user)
    {
        switch ($user->role) {
            case 'patient':
                self::removePatient($user->patient->id);
                break;
            case 'practitioner':
                self::removePractitioner($user->practitioner->id);
                break;
        }
    }

    /**
     * Annulation de tous les rendez-vous à venir pour un praticien
     *
     * @return void
     */
    public static function removePractitioner($idPractitioner)
    {
        // recherche tous les rendez-vous
        $appointments = self::getUpcommingAppointmentPractitioner($idPractitioner);

        // annule tous les rendez-vous
        foreach ($appointments as $appointment) {
            // seul le patient sera notifié par email de l'annulation du rendez-vous
            self::delete($appointment, true, ['patient']);
        }
    }

    /**
     * Annulation de tous les rendez-vous à venir pour un patient
     *
     * @return void
     */
    public static function removePatient($idPatient)
    {
        // recherche tous les rendez-vous
        $appointments = self::getUpcommingAppointmentPatient($idPatient);

        // annule tous les rendez-vous
        foreach ($appointments as $appointment) {
            // seul le praticien sera notifié par email de l'annulation du rendez-vous
            self::delete($appointment, true, ['practitioner']);
        }
    }

    /**
     * Récupère les rendez-vous à venir pour un praticien
     *
     * @param $idPractitioner
     * @return Collection|null
     */
    public static function getUpcommingAppointmentPractitioner($idPractitioner): ?Collection
    {
        return Appointment::where("meet_at", ">", Carbon::now())
            ->where("status", "active")
            ->where("practitioner_id", $idPractitioner)
            ->orderBy("meet_at")
            ->get();
    }

    /**
     * Récupère les rendez-vous passés pour un praticien
     *
     * @param $idPractitioner
     * @return Collection|null
     */
    public static function getPreviousAppointmentPractitioner($idPractitioner): ?Collection
    {
        return Appointment::where("meet_at", ">", Carbon::now())
            ->where("status", "active")
            ->where("practitioner_id", $idPractitioner)
            ->orderBy("meet_at", "DESC")
            ->get();
    }

    /**
     * Récupère les rendez-vous à venir pour un patient
     *
     * @param $idPatient
     * @return Collection|null
     */
    public static function getUpcommingAppointmentPatient($idPatient): ?Collection
    {
        return Appointment::where("meet_at", ">", Carbon::now())
            ->where("status", "active")
            ->where("patient_id", $idPatient)
            ->orderBy("meet_at")
            ->get();
    }

    /**
     * Récupère les rendez-vous passés pour un patient
     *
     * @param $idPatient
     * @return Collection|null
     */
    public static function getPreviousAppointmentPatient($idPatient): ?Collection
    {
        return Appointment::where("meet_at", "<", Carbon::now())
            ->where("status", "active")
            ->where("patient_id", $idPatient)
            ->orderBy("meet_at", "DESC")
            ->get();
    }
}
