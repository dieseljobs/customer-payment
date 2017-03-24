<?php

namespace TheLHC\CustomerPayment;

use Illuminate\Support\ServiceProvider;

class CustomerPaymentServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/customer_payment.php' => config_path('customer_payment.php')
        ], 'config');
    }
    
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/customer_payment.php', 'customer_payment');

        $this->app->singleton('customer_payment', function ($app) {
            return new CustomerPaymentManager($app);
        });

        $this->app->singleton('customer_payment.driver', function ($app) {
            return $app['customer_payment']->driver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'customer_payment', 'customer_payment.driver',
        ];
    }

}
