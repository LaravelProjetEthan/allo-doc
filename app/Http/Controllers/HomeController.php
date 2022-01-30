<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Practitioner;

class HomeController extends Controller
{
    /**
     * Page d'accueil du site
     *
     * @return Application|Factory|View
     */
    public function home()
    {
        // liste des spécialités pour le moteur de recherche
        $specialities = Speciality::all();

        // liste des villes des practiciens pour le moteur de recherche
        $practitioners = Practitioner::all();
        $cities = $practitioners->unique('city');

        return view('home', [
            "specialities" => $specialities,
            "cities" => $cities
        ]);
    }
}
