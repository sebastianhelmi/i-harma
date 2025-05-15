<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Allow viewing any project
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow all users to view projects list
    }

    /**
     * Allow viewing the project
     */
    public function view(User $user, Project $project): bool
    {
        return true; // Allow all users to view project details
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->manager_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->manager_id;
    }
}
