<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project)
    {
        return $user->id === $project->user_id; // L'utilisateur peut voir le projet s'il en est le propriétaire
    }

    public function create(User $user)
    {
        return true; // Tous les utilisateurs authentifiés peuvent créer des projets
    }

    public function update(User $user, Project $project)
    {
        return $user->id === $project->user_id; // L'utilisateur peut mettre à jour le projet s'il en est le propriétaire
    }

    public function delete(User $user, Project $project)
    {
        return $user->id === $project->user_id; // L'utilisateur peut supprimer le projet s'il en est le propriétaire
    }
}