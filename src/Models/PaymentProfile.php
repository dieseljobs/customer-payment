<?php

namespace TheLHC\CustomerPayment\Models;

use TheLHC\CustomerPayment\PaymentModelProvider;

class PaymentProfile extends PaymentModelProvider
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_profiles';

    /**
     * User timestamps columns
     *
     * @var boolean
     */
    public $timestamps = false;

}
