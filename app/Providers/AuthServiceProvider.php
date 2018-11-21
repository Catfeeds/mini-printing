<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\User;
use App\Policies\OrderPolicy;
use App\Policies\OrderRefundPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Address;
use App\Policies\AddressPolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        OrderRefund::class => OrderRefundPolicy::class,
        User::class => UserPolicy::class,
        Address::class => AddressPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
