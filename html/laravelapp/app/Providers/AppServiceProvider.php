<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // インタフェースやめた
        /*
        $this->app->bind(
            \App\Repositories\ThreadRepositoryInterface::class,
            \App\Repositories\ThreadRepository::class
        );
        $this->app->bind(
            \App\Repositories\ReplyRepositoryInterface::class,
            \App\Repositories\ReplyRepository::class
        );
        $this->app->bind(
            \App\Services\ThreadServiceInterface::class,
            \App\Services\ThreadService::class,
        );
        $this->app->bind(
            \App\Services\ReplyServiceInterface::class,
            \App\Services\ReplyService::class,
        );
        $this->app->bind(
            \App\Services\UtilServiceInterface::class,
            \App\Services\UtilService::class,
        );
        $this->app->bind(
            \App\Services\UserServiceInterface::class,
            \App\Services\UserService::class
        );
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        */
        $this->app->bind(
            \Illuminate\Pagination\LengthAwarePaginator::class,
            \App\Http\Paginators\YuzuPaginator::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
