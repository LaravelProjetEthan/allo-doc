<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\Repositories\AppointmentRepository;
use App\Services\DateHelper;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    /**
     * Affiche les rendez-vous du patient connecté
     *
     * @return Application|Factory|View
     */
    public function patient()
    {
        $idPatient = Auth::user()->patient->id;
        $upcomingAppointments = AppointmentRepository::getUpcommingAppointmentPatient($idPatient);
        $previousAppointments = AppointmentRepository::getPreviousAppointmentPatient($idPatient);

        return view('appointment/patient', [
            'upcomingAppointments' => $upcomingAppointments,
            'previousAppointments' => $previousAppointments,
            'dateHelper'           => new DateHelper(),
        ]);
    }

    /**
     * Suppression d'un rendez-vous par le patient
     *
     * @param Request $request
     * @param int $id_appointment
     * @return Application|RedirectResponse|Redirector
     */
    public function patientAppointmentDelete(Request $request, int $id_appointment)
    {
        // recherche le rendez-vous
        $appointment = Appointment::find($id_appointment);
        // affichage d'un message d'erreur si le rendez-vous n'existe pas
        if (!$appointment) {
            abort(404);
        }
        // affichage d'un message d'erreur si ce n'est pas le patient qui demande l'annulation
        if ($appointment->patient_id != Auth::user()->patient->id) {
            abort(403);
        }
        // supprime le rendez-vous et notifie le praticien
        AppointmentRepository::delete($appointment, true, ['practitioner']);

        return redirect(route('my-appointment-patient'));
    }

    /**
     * Affiche les rendez-vous du praticien connecté
     *
     * @return view
     */
    public function practitioner(): View
    {
        $idPractitioner = Auth::user()->practitioner->id;
        $upcomingAppointmentsData = AppointmentRepository::getUpcommingAppointmentPractitioner($idPractitioner);
        // groupe les rendez-vous par journée
        $upcomingAppointments = [];

        foreach ($upcomingAppointmentsData as $appointment) {
            $meet_at = (new Carbon($appointment->meet_at))->format("Y-m-d");
            if (!isset($upcomingAppointments[$meet_at])) {
                $upcomingAppointments[$meet_at] = [];
            }
            $upcomingAppointments[$meet_at][] = $appointment;
        }

        return view('appointment/practitioner', [
            'upcomingAppointments' => $upcomingAppointments,
            'dateHelper'           => new DateHelper(),
        ]);
    }

    /**
     * Suppression d'un rendez-vous par le praticien
     *
     * @param Request $request
     * @param int $id_appointment
     * @return RedirectResponse
     */
    public function practitionerAppointmentDelete(Request $request, int $id_appointment): RedirectResponse
    {
        // recherche le rendez-vous
        $appointment = Appointment::find($id_appointment);
        // affichage d'un message d'erreur si le rendez-vous n'existe pas
        if (!$appointment) {
            abort(404);
        }
        // affichage d'un message d'erreur si ce n'est pas le patient qui demande l'annulation
        if ($appointment->practitioner_id != Auth::user()->practitioner->id) {
            abort(403);
        }
        // supprime le rendez-vous et notifie le patient
        AppointmentRepository::delete($appointment, true, ['patient']);

        return redirect(route('my-appointment-practitioner'));
    }
}
