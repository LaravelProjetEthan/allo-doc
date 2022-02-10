<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PractitionerController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// page d'accueil
Route::get('/', [HomeController::class, 'home'])->name("home");

// page de résultats de la recherche d'un praticien
Route::post('/practitioners/search', [PractitionerController::class, 'search'])->name('search-practitioner');

// fiche d'information d'un praticien
Route::get('/practitioner/{id}/{start_date?}', [PractitionerController::class, 'detail'])->name('detail-practitioner');

// Inscription d'un patient
Route::get('/signup', [UserController::class, 'signupPatient'])->name("signup");
Route::post('/signup', [UserController::class, 'signupPatientPost'])->name("signupPost");

// déclaration de la route qui affichera le formulaire de connexion
Route::get('login', [UserController::class, 'login'])->name("login");
Route::post('login', [UserController::class, 'loginPost'])->name("loginPost");

// Validation de l'adresse email d'un nouvel utilisateur
Route::get('/valid-email/{idUser}/{token}', [UserController::class, 'validEmail'])->name('validEmail');

// les routes ci-dessous sont limités aux utilisateurs connectés
Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // enregistrement d'un rendez-vous
    Route::get('/practitioner/{id_practitioner}/appointment/{date_appointment}/{hour_appointment}', [PractitionerController::class, 'appointment'])->name("appointment")->middleware('acl:patient');
    Route::post('/practitioner/{id_practitioner}/appointment/{date_appointment}/{hour_appointment}', [PractitionerController::class, 'appointmentPost'])->name("appointmentPost")->middleware('acl:patient');

    // page profil
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'profilePost'])->name('profilePost');
    Route::delete('/profile', [UserController::class, 'delete'])->name('profileDelete');

    // Mes rendez-vous (patient)
    Route::get('/my-appointment/patient', [AppointmentController::class, 'patient'])->name("my-appointment-patient")->middleware('acl:patient');
    Route::delete('/my-appointment/patient/delete/{id_appointment}', [AppointmentController::class, 'patientAppointmentDelete'])->middleware('acl:patient');

    // Mes rendez-vous (praticien)
    Route::get('/my-appointment/practitioner', [AppointmentController::class, 'practitioner'])->name("my-appointment-practitioner")->middleware('acl:practitioner');
    Route::delete('/my-appointment/practitioner/delete/{id_appointment}', [AppointmentController::class, 'practitionerAppointmentDelete'])->middleware('acl:practitioner');

    Route::group(['middleware' => ['acl:administrator']], function () {
        // CRUD Praticien
        Route::get('/practitioners', [PractitionerController::class, 'index'])->name('practitioner.index');
        Route::get('/practitioners/create', [PractitionerController::class, 'create'])->name('practitioner.create');
        Route::get('/practitioners/{id}/edit', [PractitionerController::class, 'edit'])->name('practitioner.edit');
        Route::post('/practitioners', [PractitionerController::class, 'store'])->name('practitioner.store');
        Route::delete('/practitioners/{id}', [PractitionerController::class, 'destroy'])->name('practitioner.destroy');

        // CRUD Patient
        Route::get('/patients', [PatientController::class, 'index'])->name('patient.index');
        Route::get('/patients/create', [PatientController::class, 'create'])->name('patient.create');
        Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patient.edit');
        Route::post('/patients', [PatientController::class, 'store'])->name('patient.store');
        Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');

        // CRUD Spécialités
        Route::get('/specialties', [SpecialityController::class, 'index'])->name('speciality.index');
        Route::get('/specialties/create', [SpecialityController::class, 'create'])->name('speciality.create');
        Route::get('/specialties/{id}/edit', [SpecialityController::class, 'edit'])->name('speciality.edit');
        Route::post('/specialties', [SpecialityController::class, 'store'])->name('speciality.store');
        Route::delete('/specialties/{id}', [SpecialityController::class, 'destroy'])->name('speciality.destroy');
    });
});