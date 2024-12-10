<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données reçues
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'nullable|string',
            'json_structure' => 'nullable|array',
            'thumbnail_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validation du fichier image
            'deployment_link' => 'nullable|string|url',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('thumbnail_url')) {
            // Stocker l'image et récupérer son chemin
            $path = $request->file('thumbnail_url')->store('templates/thumbnails', 'public');
            $validated['thumbnail_url'] = $path; // Enregistrer le chemin de l'image dans la base de données
        }

        // Création du template avec les données validées
        $template = Template::create($validated);

        // Retourner la réponse
        return response()->json($template, 201);
    }

    public function index()
    {
        // Récupérer tous les templates
        $templates = Template::all();

        // Retourner les templates
        return response()->json($templates);
    }
}
