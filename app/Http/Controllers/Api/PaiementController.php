<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Http\Requests\StorePaiementRequest; // Assuming you have a request class for validation

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Paiement::with('contrat')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaiementRequest $request)
    {
        $data = $request->validated();
        return Paiement::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        return $paiement;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paiement $paiement)
    {
        $data = $request->validate([
            'contrat_id' => 'sometimes|required|exists:contrats,id',
            'montant' => 'sometimes|required|numeric',
            'statut' => 'sometimes|required|string',
            'date_echeance' => 'sometimes|required|date',
            'date_paiement' => 'sometimes|nullable|date|after:date_echeance',
        ]);

        $paiement->update($data);
        return $paiement;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return response()->noContent();
    }
}
