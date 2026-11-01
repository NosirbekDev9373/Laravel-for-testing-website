<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Post;
use App\Policies\PostPolicy;



class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Paginator::useBootstrapFour();
        JsonResource::withoutWrapping();

        //Policy faylini
        Gate::policy(Post::class, PostPolicy::class);

    }
}
