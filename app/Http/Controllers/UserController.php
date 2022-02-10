<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentNotification;
use App\Mail\UserRegisterNotification;
use App\Models\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Repositories\AppointmentRepository;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Affichage du formulaire de connexion
     *
     * @return Application|Factory|View
     */
    public function login()
    {
        return view('user/login');
    }

    /**
     * Connexion de l'utilisateur ou rejet
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginPost(Request $request): RedirectResponse
    {
        // règle de validation du formulaire d'inscription
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Filtre les comptes actifs
        $credentials["status"] = "active";

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Les informations fournies ne correspondent à un utilisateur enregistré',
        ]);
    }

    /**
     * Déconnexion d'un utilisateur
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function logout(Request $request)
    {
        // invalidation de la session pour déconnecter l'utilisateur
        $request->session()->invalidate();

        // redirection vers la page de login
        return redirect(route('login'));
    }

    /**
     * Formulaire d'inscription d'un patient
     *
     * @return view
     */
    public function signupPatient()
    {
        return view('user/signupPatient');
    }

    /**
     * Enregistrement du patient et de l'utilisateur associé
     *
     * @param Request $request
     * @return view
     */
    public function signupPatientPost(Request $request): View
    {
        // règle de validation du formulaire d'inscription
        $request->validate([
            'firstname'       => 'required|max:255',
            'lastname'        => 'required|max:255',
            'address'         => 'required|max:255',
            'zipcode'         => 'required|max:5',
            'city'            => 'required|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8',
            'passwordConfirm' => 'required_with:password|same:password',
        ]);

        // création de l'utilisateur
        $user = new User();
        $user->name = $request->input("firstname") . ' ' . $request->input("lastname");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->status = 'inactive';
        $user->verification_code = sha1(time());
        $user->role = 'patient';
        $user->save();

        // création du patient
        $patient = new Patient();
        $patient->firstname = $request->input("firstname");
        $patient->lastname = $request->input("lastname");
        $patient->email = $request->input("email");
        $patient->address = $request->input("address");
        $patient->zipcode = $request->input("zipcode");
        $patient->city = $request->input("city");
        $patient->user_id = $user->id;
        $patient->save();

        if (!empty($user)) {
            UserRepository::sendEmailInscription($user);
        }

        // affichage de la vue de confirmation d'inscription
        return view('user/signupPatientConfirmation');
    }

    /**
     * Affichage du formulaire profil utilisateur
     *
     * @return Application|Factory|View
     */
    public function profile()
    {
        return view('user/profile');
    }

    /**
     * Sauvegarde des informations du profil
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function profilePost(Request $request)
    {

        if (Auth::user()->role == 'administrator') {
            // règle de validation du formulaire d'inscription pour les administrateurs
            $request->validate([
                'email'           => 'required|email|unique:users,email,' . Auth::user()->id,
                'passwordConfirm' => 'required_with:password|same:password',
            ]);
        } else {
            // règle de validation du formulaire d'inscription pour les patients et praticiens
            $request->validate([
                'firstname'       => 'required|max:255',
                'lastname'        => 'required|max:255',
                'address'         => 'required|max:255',
                'zipcode'         => 'required|max:5',
                'city'            => 'required|max:255',
                'email'           => 'required|email|unique:users,email,' . Auth::user()->id,
                'passwordConfirm' => 'required_with:password|same:password',
            ]);
        }

        // sauvegarde des données spécifiques d'un patient
        if (Auth::user()->role == 'patient') {
            Auth::user()->patient->firstname = $request->input("firstname");
            Auth::user()->patient->lastname = $request->input("lastname");
            Auth::user()->patient->email = $request->input("email");
            Auth::user()->patient->address = $request->input("address");
            Auth::user()->patient->zipcode = $request->input("zipcode");
            Auth::user()->patient->city = $request->input("city");
            Auth::user()->patient->save();

            Auth::user()->name = $request->input("firstname") . " " . $request->input("lastname");
        }

        // sauvegarde des données spécifiques d'un praticien
        if (Auth::user()->role == 'practitioner') {
            Auth::user()->practitioner->firstname = $request->input("firstname");
            Auth::user()->practitioner->lastname = $request->input("lastname");
            Auth::user()->practitioner->email = $request->input("email");
            Auth::user()->practitioner->address = $request->input("address");
            Auth::user()->practitioner->zipcode = $request->input("zipcode");
            Auth::user()->practitioner->city = $request->input("city");
            Auth::user()->practitioner->save();

            Auth::user()->name = $request->input("firstname") . " " . $request->input("lastname");
        }

        // Sauvegarde des données de l'utilisateur
        Auth::user()->email = $request->input("email");

        // changement du mot de passe uniquement si le mot de passe est changé
        if ($request->input("password")) {
            Auth::user()->password = Hash::make($request->input("password"));
        }

        Auth::user()->save();

        return redirect(route('profile'));
    }

    /**
     * Suppression du compte utilisateur
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function delete(Request $request)
    {
        // annule tous les rendez-vous d'un utilisateur
        AppointmentRepository::removeUser(Auth::user());

        // Change le statut du compte utilisateur
        Auth::user()->status = 'suspended';
        Auth::user()->save();

        // redirige l'utilisateur vers la page de déconnexion
        return redirect(route('logout'));
    }

    /**
     * Règle de validation pour l'adresse email
     *
     * @param $idUser
     * @param $token
     * @return Application|Factory|View
     */
    public function validEmail($idUser, $token)
    {
        $user = User::find($idUser);

        // Message d'erreur si l'utilisateur n'existe pas
        if (!$user) {
            abort('404');
        }

        // message d'erreur si le token n'est pas bon
        if ($user->verification_code != $token) {
            abort('403');
        }

        $user->email_verified_at = Carbon::now();
        $user->status = 'active';
        $user->save();

        return view('user/userRegistrationConfirmation');
    }
}
