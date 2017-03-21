<?php

namespace TheLHC\CustomerPayment\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentProcessor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'customer_payment';
    }
}
