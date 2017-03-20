<?php

namespace TheLHC\CustomerPayment;

use Illuminate\Support\Manager;

class CustomerPaymentManager extends Manager
{

    /**
     * Get the default payment processor name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['customer_payment.default'];
    }

    /**
     * Create an instance of the Stripe driver.
     *
     * @return PaymentProcessor
     */
    public function createStripeDriver()
    {
        $provider = $this->createStripeProvider();

        return new PaymentProcessor($provider);
    }

    /**
     * Create an instance of the Stripe payment provider.
     *
     * @return StripeProvider
     */
    protected function createStripeProvider()
    {
        $config = $this->app['config']['customer_payment.drivers']['stripe'];

        return new StripePaymentProvider($config);
    }
}
