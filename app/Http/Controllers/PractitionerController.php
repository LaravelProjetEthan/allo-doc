<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Speciality;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\Repositories\AppointmentRepository;
use Illuminate\Support\Carbon;
use App\Services\DateHelper;
use Arr;
use App\Services\PractitionerPlanning;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PractitionerController extends Controller
{
    /**
     * Controller qui gère l'affichage des résultats de recherche d'un praticien.
     * Le planning de chaque praticien sera affiché en désactivant les créneaux déjà pris.
     * Possibilité de naviguer dans le planning avec "Semaine suivante" et "Semaine précédente"
     *
     * @param PractitionerPlanning $practitionerPlanning
     * @param Request $request
     * @return Application|Factory|View
     */
    public function search(PractitionerPlanning $practitionerPlanning, Request $request)
    {
        // récupère les données postées
        $speciality_id = $request->input('speciality_id');
        $city = $request->input('city');
        $start_date_post = $request->input('start_date', ""); // pour les formulaires semaine suivante et précédente

        // Lit dans la bdd la Spécialité sélectionnée
        $speciality = Speciality::find($speciality_id);

        // Recherche du praticien en fonction des critères
        $practitioners = Practitioner::select("*");

        // applique le filtre "Spécialité"
        if ($speciality) {
            $practitioners = $practitioners->where("speciality_id", $speciality_id);
        }

        // applique le filtre "Ville"
        if ($city) {
            $practitioners = $practitioners->where("city", $city);
        }

        // récupération des résultats
        $practitioners = $practitioners->get();

        // Spécialités pour la liste déroulante du moteur de recherche
        $specialties = Speciality::all();

        // Villes des praticiens pour la liste déroulantes du moteur de recherche
        $cities = $this->getCitiesList();

        // mémorise la date de début pour le planning
        $start_date_save = $start_date_post == "" ? Carbon::now() : new Carbon($start_date_post);

        // calcul la date de la semaine précédente
        $date_previous_week = DateHelper::firstDayOfPreviousWeek($start_date_save);

        // calcul la date de la semaine suivante
        $date_next_week = DateHelper::firstDayOfNextWeek($start_date_save);

        // récupère les différents slots pour les praticiens
        foreach ($practitioners as $practitioner) {
            $start_date = (new Carbon($start_date_save))->startOfWeek();
            // Récupère les plages disponibles pour le praticien
            $practitioner->slots = $practitionerPlanning->get($practitioner, $start_date);
        }

        return view('practitioner/search', [
            "date_previous_week" => $date_previous_week,
            "date_next_week"     => $date_next_week,
            "speciality"         => $speciality,
            "city"               => $city,
            "speciality_id"      => $speciality_id,
            "specialties"        => $specialties,
            "cities"             => $cities,
            "practitioners"      => $practitioners,
        ]);
    }

    /**
     * Page d'information sur un praticien avec affichage de son planning
     * et possibilité de naviguer dedans.
     *
     * @param PractitionerPlanning $practitionerPlanning
     * @param [type] $id
     * @param string $start_date
     * @return Application|Factory|View
     */
    public function detail(PractitionerPlanning $practitionerPlanning, $id, string $start_date = "")
    {
        // Spécialités pour la liste déroulantes du moteur de recherche
        $specialties = Speciality::all();

        // Villes des praticiens pour la liste déroulantes du moteur de recherche
        $practitionersForCity = Practitioner::all();
        $cities = $practitionersForCity->unique('city');

        // charge les infos du praticien
        $practitioner = Practitioner::find($id);

        // si le praticien n'existe pas dans la base, on affiche une page 404
        if (!$practitioner) {
            abort(404);
        }

        // mémorise la date de début du planning
        $start_date_save = $start_date == "" ? Carbon::now() : new Carbon($start_date);

        // calcul la date de la semaine précédente
        $date_previous_week = DateHelper::firstDayOfPreviousWeek($start_date);

        // calcul la date de la semaine suivante
        $date_next_week = DateHelper::firstDayOfNextWeek($start_date_save);

        // recherche le planning du praticien
        $start_date = (new Carbon($start_date_save))->startOfWeek();
        $practitioner->slots = $practitionerPlanning->get($practitioner, $start_date);

        return view('practitioner/show', [
            "date_previous_week" => $date_previous_week,
            "date_next_week"     => $date_next_week,
            "specialties"        => $specialties,
            "cities"             => $cities,
            "practitioner"       => $practitioner,
        ]);
    }

    /**
     * Affichage du formulaire de saisie du motif du rendez-vous
     *
     * @param integer $id_practitioner
     * @param string $date_appointment
     * @param integer $hour_appointment
     * @return Application|Factory|View
     */
    public function appointment(int $id_practitioner, string $date_appointment, int $hour_appointment)
    {
        $practitioner = Practitioner::find($id_practitioner);

        // affiche une erreur 404 si le praticien n'existe pas dans la table
        if (!$practitioner || $practitioner->user->status != 'active') {
            abort(404);
        }

        return view('practitioner/appointment', [
            'practitioner'     => $practitioner,
            'id_practitioner'  => $id_practitioner,
            'date_appointment' => $date_appointment,
            'hour_appointment' => $hour_appointment,
            'dateHelper'       => new DateHelper(),
        ]);
    }

    /**
     * Sauvegarde d'un rendez-vous
     *
     * @param Request $request
     * @param integer $id_practitioner
     * @param string $date_appointment
     * @param integer $hour_appointment
     * @return Application|Factory|View|RedirectResponse
     */
    public function appointmentPost(Request $request, int $id_practitioner, string $date_appointment, int $hour_appointment)
    {
        $request->validate([
            'reason' => 'required|min:10|max:255',
        ]);

        // affiche une erreur 404 si le praticien n'existe pas dans la table
        $practitioner = Practitioner::find($id_practitioner);
        if (!$practitioner || $practitioner->user->status != 'active') {
            abort(404);
        }

        // retrouve l'ID du patient associé à l'utilisateur connecté
        $patient_id = Auth::user()->patient->id;

        // string complete pour la date et heure du rendez-vous
        $date_time_appointment = $date_appointment . ' ' . $hour_appointment . ':00:00';

        // Vérifie qu'un praticien n'a pas déjà un rendez-vous à la même heure
        $appointment = Appointment::where("meet_at", $date_time_appointment)->where("practitioner_id", $id_practitioner)->where("status", "active")->first();
        if ($appointment) {
            return back()->withErrors([
                'reason' => 'La praticien a déjà un rendez-vous sur ce même créneau, veuillez en choisir un autre',
            ]);
        }

        // enregistre le rendez-vous
        $appointment = new Appointment();
        $appointment->patient_id = $patient_id;
        $appointment->practitioner_id = $id_practitioner;
        $appointment->reason = $request->input('reason');
        $appointment->meet_at = $date_time_appointment;
        $appointment->status = 'active';
        $appointment->save();

        // Confirmation du rendez-vous
        return view('practitioner/appointmentConfirmation', [
            'practitioner'     => $practitioner,
            'id_practitioner'  => $id_practitioner,
            'date_appointment' => $date_appointment,
            'hour_appointment' => $hour_appointment,
            'reason'           => $request->input('reason'),
            'dateHelper'       => new DateHelper(),
        ]);
    }

    /**
     * Listing de tous les praticiens
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Set potential filters
        $filtered_by_name = $request->input('lastname', null);
        $filtered_by_city = $request->input('city', null);
        $filtered_by_speciality = $request->input('speciality', null);

        // Initiate Eloquent query
        $qry_practitioners = Practitioner::query();

        // Add potential filters to Eloquent query
        if ($filtered_by_name) {
            $qry_practitioners = $qry_practitioners->where('lastname', 'LIKE', '%' . $filtered_by_name . '%');
        }

        if ($filtered_by_city) {
            $qry_practitioners = $qry_practitioners->where('city', '=', $filtered_by_city);
        }

        if ($filtered_by_speciality) {
            $qry_practitioners = $qry_practitioners->where('speciality_id', '=', $filtered_by_speciality);
        }

        // Ordering by lastname
        $qry_practitioners = $qry_practitioners->orderBy('lastname', 'ASC');

        // Make Eloquent request
        // Add speciality relation to avoid n+1 problem in view
        // https://laravel.com/docs/8.x/eloquent-relationships#eager-loading
        $practitioners = $qry_practitioners->with('speciality')->get();

        return view('practitioner.index', [
            'practitioners'      => $practitioners,
            'specialties_filter' => Speciality::all(),
            'cities_filter'      => $this->getCitiesList(),
        ]);
    }

    /**
     * Formulaire de création
     *
     * @return View
     */
    public function create(): View
    {
        return view('practitioner.create', [
            'specialties'  => Speciality::all(),
            'practitioner' => new Practitioner(),
        ]);
    }

    /**
     * Formulaire de modification
     *
     * @param int $id practitioner's id to update
     * @return View
     */
    public function edit(int $id): View
    {
        $practitioner = Practitioner::find($id);
        return view('practitioner.edit', [
            'practitioner' => $practitioner,
            'specialties'  => Speciality::all(),
        ]);
    }

    /**
     * Sauvegarde des données du praticien
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $id = $request->input("id", null);

        // Initialisation par défaut des modèles à vide
        $user = new User();
        $practitioner = new Practitioner();

        // charge le praticien et l'utilisateur associé lors de la modification
        if ($id) {
            $practitioner = Practitioner::find($id);
            $user = $practitioner->user;
        }

        // règles de validation
        $rules = [
            'firstname'     => 'required|max:255',
            'lastname'      => 'required|max:255',
            'email'         => 'required|email|unique:users,email',
            'address'       => 'required|max:255',
            'zipcode'       => 'required|size:5',
            'city'          => 'required|max:255',
            'speciality_id' => 'required',
        ];

        // spécifique à la création
        if (!$id) {
            $rules['password'] = 'required|min:8|max:255';
        }

        // spécifique à la modification
        if ($id) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        // Validation des données
        $request->validate($rules);

        // sauvegarde de données de l'utilisateur
        $user->name = $request->input('firstname') . " " . $request->input('lastname');
        $user->email = $request->input('email');
        // le mot de passe est pas obligatoire uniquement en création
        if (!$id || $request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->status = $id ? $user->status : 'active';
        $user->role = 'practitioner';
        $user->save();

        // Sauvegarde du praticien
        $practitioner->user_id = $user->id;
        $practitioner->firstname = $request->input('firstname');
        $practitioner->lastname = $request->input('lastname');
        $practitioner->email = $request->input('email');
        $practitioner->address = $request->input('address');
        $practitioner->zipcode = $request->input('zipcode');
        $practitioner->city = $request->input('city');
        $practitioner->speciality_id = $request->input('speciality_id');
        $practitioner->save();

        return redirect()->route('practitioner.index');
    }

    /**
     * Supprime le praticien
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $practitionerToDelete = Practitioner::find($id);
        if ($practitionerToDelete) {
            // annule tous les rendez-vous à venir
            AppointmentRepository::removeUser($practitionerToDelete->user);

            // Supprime le praticien
            $practitionerToDelete->delete();

            // Supprime l'utilisateur
            $practitionerToDelete->user->delete();
        }

        return redirect()->route('practitioner.index');
    }

    /**
     * Get cities filter list
     * @return array
     */
    private function getCitiesList(): array
    {
        // https://laravel.com/docs/8.x/helpers#method-array-flatten
        return array_unique(Arr::flatten(Practitioner::select('city')->get()->toArray()));
    }
}
