<?php

namespace App\Providers;

use App\Post;
use App\Post_comment;
use App\Policies\PostPolicy;
use App\Policies\CommentPolicy;
use App\Policies\BlockPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
         Post::class => PostPolicy::class,
         Post_comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('check', 'App\Policies\BlockPolicy@check');

        //
    }
}
