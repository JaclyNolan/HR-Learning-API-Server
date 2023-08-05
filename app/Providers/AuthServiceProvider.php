<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function(User $user) {
            return $user->role->name == 'admin'
            ? Response::allow()
            : Response::deny();
        });
        Gate::define('isStaff', function(User $user) {
            return $user->role->name == 'staff'
            ? Response::allow()
            : Response::deny();
        });
        Gate::define('isAdminOrStaff', function(User $user) {
            return $user->role->name == 'staff' || $user->role->name == 'admin'
            ? Response::allow()
            : Response::deny();
        });
        Gate::define('isTrainer', function(User $user) {
            return $user->role->name == 'trainer'
            ? Response::allow()
            : Response::deny();
        });
    }
}
