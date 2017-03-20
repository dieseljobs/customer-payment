<?php

namespace TheLHC\CustomerPayment;

use Illuminate\Support\Facades\Facade;

class CustomerPaymentFacade extends Facade
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
