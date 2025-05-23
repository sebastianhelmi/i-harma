<?php

namespace App\Providers;

use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);

        // Define Project Manager gate
        Gate::define('view-projects', function ($user) {
            return in_array($user->role, ['purchasing', 'admin', 'project-manager']);
        });

        $spbDocumentsPath = Storage::disk('public')->path('spb-documents');
        if (!file_exists($spbDocumentsPath)) {
            mkdir($spbDocumentsPath, 0755, true);
        }
    }
}
