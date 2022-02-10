<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\Repositories\AppointmentRepository;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    /**
     * Listing de tous les patients
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $qry_patients = Patient::query();
        $qry_patients = $qry_patients->orderBy('lastname', 'ASC');
        $patients = $qry_patients->get();

        return view('patient.index', [
            'patients' => $patients,
        ]);
    }

    /**
     * Formulaire de création
     *
     * @return View
     */
    public function create(): View
    {
        return view('patient.create', [
            'patient' => new Patient(),
        ]);
    }

    /**
     * Formulaire de modification
     *
     * @param int $id Id du patient à modifier
     * @return View
     */
    public function edit(int $id): View
    {
        $patient = Patient::find($id);
        return view('patient.edit', [
            'patient' => $patient,
        ]);
    }

    /**
     * Sauvegarde des données du patient
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $id = $request->input("id", null);
        // Initialisation par défaut des modèles à vide
        $user = new User();
        $patient = new Patient();
        // charge le patient et l'utilisateur associé lors de la modification
        if ($id) {
            $patient = Patient::find($id);
            $user = $patient->user;
        }
        // règles de validation
        $rules = [
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|unique:users,email',
            'address'   => 'required|max:255',
            'zipcode'   => 'required|size:5',
            'city'      => 'required|max:255',
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
        $user->role = 'patient';
        $user->save();

        // Sauvegarde du patient
        $patient->user_id = $user->id;
        $patient->firstname = $request->input('firstname');
        $patient->lastname = $request->input('lastname');
        $patient->email = $request->input('email');
        $patient->address = $request->input('address');
        $patient->zipcode = $request->input('zipcode');
        $patient->city = $request->input('city');
        $patient->save();

        return redirect()->route('patient.index');
    }

    /**
     * Supprime le patient
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $patientToDelete = Patient::find($id);
        if ($patientToDelete) {
            // annule tous les rendez-vous à venir
            AppointmentRepository::removeUser($patientToDelete->user);

            // Supprime le patient
            $patientToDelete->delete();

            // Supprime l'utilisateur
            $patientToDelete->user->delete();
        }

        return redirect()->route('patient.index');
    }
}
