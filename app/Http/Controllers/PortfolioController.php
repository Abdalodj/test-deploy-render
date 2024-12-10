<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'portfolio_type' => 'required|string',
                'nom_projet' => 'required|string',
                'description' => 'nullable|string',
                'template_type' => 'required|string',
                'sections' => 'required|array'
            ]);

            $portfolio = new Portfolio();
            $portfolio->user_id = Auth::id(); 
            $portfolio->title = $validatedData['title'];
            $portfolio->portfolio_type = $validatedData['portfolio_type'];
            $portfolio->nom_projet = $validatedData['nom_projet'];
            $portfolio->description = $validatedData['description'] ?? null;
            $portfolio->template_type = $validatedData['template_type'];
            $portfolio->sections = $validatedData['sections'];
            $portfolio->save();

            return response()->json([
                'message' => 'Portfolio créé avec succès',
                'portfolio' => $portfolio
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creating portfolio:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error creating portfolio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Portfolio $portfolio)
    {
    if ($portfolio->user_id !== Auth::id()) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }
    return response()->json($portfolio);
    }

    public function updateSectionOrder(Request $request, Portfolio $portfolio)
    {
    if ($portfolio->user_id !== Auth::id()) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }

    $request->validate([
        'sections' => 'required|array',
        'sections.*.id' => 'required|string',
        'sections.*.order' => 'required|integer|min:1'
    ]);

    $sections = collect($portfolio->sections);
    
    foreach ($request->sections as $updatedSection) {
        $sections->transform(function ($section) use ($updatedSection) {
            if ($section['id'] === $updatedSection['id']) {
                $section['order'] = $updatedSection['order'];
            }
            return $section;
        });
    }

    $orderedSections = $sections->sortBy('order')->values()->all();
    
    $portfolio->update(['sections' => $orderedSections]);

    return response()->json([
        'message' => 'Ordre des sections mis à jour',
        'portfolio' => $portfolio
    ]);
}

public function update(Request $request, Portfolio $portfolio)
{
    if ($portfolio->user_id !== Auth::id()) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }

    $validatedData = $request->validate([
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|nullable|string',
        'template_type' => 'sometimes|string',
        'sections' => 'sometimes|array'
    ]);

    $portfolio->update($validatedData);

    return response()->json([
        'message' => 'Portfolio mis à jour avec succès',
        'portfolio' => $portfolio
    ]);
}

public function destroy(Portfolio $portfolio)
{
    if ($portfolio->user_id !== Auth::id()) {
        return response()->json(['message' => 'Non autorisé'], 403);
    }

    try {
        $portfolio->delete();

        return response()->json([
            'message' => 'Portfolio supprimé avec succès'
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la suppression du portfolio:', ['error' => $e->getMessage()]);

        return response()->json([
            'message' => 'Erreur lors de la suppression du portfolio',
            'error' => $e->getMessage()
        ], 500);
    }
} 
}