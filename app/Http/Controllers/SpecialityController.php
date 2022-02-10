<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Speciality;
use App\Models\Practitioner;

class SpecialityController extends Controller
{
    /**
     * Listing de tous les specialties
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        // Initiate Eloquent query
        $qry_specialties = Speciality::query();

        // Ordering by name
        $qry_specialties = $qry_specialties->orderBy('name', 'ASC');

        // Make Eloquent request
        $specialties = $qry_specialties->get();

        return view('speciality.index', [
            'specialties' => $specialties
        ]);
    }

    /**
     * Formulaire de création
     *
     * @return View
     */
    public function create(): View
    {
        return view('speciality.create', [
            'speciality' => new Speciality()
        ]);
    }

    /**
     * Formulaire de modification
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id)
    {
        $speciality = Speciality::find($id);
        return view('speciality.edit', [
            'speciality' => $speciality
        ]);
    }

    /**
     * Sauvegarde des données d'une spécialité
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $id = $request->input("id", null);

        // Initialisation par défaut du modèle à vide
        $speciality = new Speciality();

        // charge la spécialité lors de la modification
        if ($id) {
            $speciality = Speciality::find($id);
        }

        // Validation des données
        $request->validate([
            'name'  => 'required|max:255'
        ]);

        // Sauvegarde de la spécialité
        $speciality->name = $request->input('name');
        $speciality->save();

        return redirect()->route('speciality.index');
    }

    /**
     * Supprime la spécialité
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function destroy(int $id)
    {
        // Tester le fait qu'un praticien possède la spécialité
        $practitionnerHasSpeciality = Practitioner::where('speciality_id', $id)->count();
        if ($practitionnerHasSpeciality) {
            return view('speciality/errorDelete');
        }

        // recherche la spécialité à supprimer
        $specialityToDelete = Speciality::find($id);
        if ($specialityToDelete) {
            // Supprime la spécialité
            $specialityToDelete->delete();
        }

        return redirect()->route('speciality.index');
    }
}
