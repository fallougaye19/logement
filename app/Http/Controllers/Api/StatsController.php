<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            "utilisateurs" => \App\Models\User::count(),
            "maisons" => \App\Models\Maison::count(),
            "chambres" => \App\Models\Chambre::count(),
            "contrats_actifs" => \App\Models\Contrat::where('actif', true)->count(),
            "rendez_vous_a_venir" => \App\Models\Rendez_vous::where("date_heure", ">", now())->count(),
            "problemes_non_resolus" => \App\Models\Probleme::where("resolu", false)->count(),
            "paiements_en_retard" => \App\Models\Paiement::where("statut", "en attente")->count()
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
