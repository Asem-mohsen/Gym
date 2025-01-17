<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\BookingRepository;
use App\Repositories\MembershipRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(UserRepository::class, function () {
            return new UserRepository();
        });

        $this->app->bind(ServiceRepository::class, function () {
            return new ServiceRepository();
        });

        $this->app->bind(MembershipRepository::class, function () {
            return new MembershipRepository();
        });

        $this->app->bind(BookingRepository::class, function () {
            return new BookingRepository();
        });

        $this->app->bind(AdminRepository::class, function () {
            return new AdminRepository();
        });

        $this->app->bind(RoleRepository::class, function () {
            return new RoleRepository();
        });
    }

    public function boot(): void
    {
        //
    }
}
