<?php

namespace TheLHC\CustomerPayment\Tests;

use TheLHC\CustomerPayment\PaymentModelProvider;

class CustomPaymentProfile extends PaymentModelProvider
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'custom_payment_profiles';

    /**
     * User timestamps columns
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'stripe_card_acct',
        'card_brand',
        'card_last_four'
    ];

    public $paymentProfileIdColumn = 'stripe_card_acct';

    public function setPaymentColumns($payment)
    {
        $this->setAttribute('card_brand', $payment->brand);
        $this->setAttribute('card_last_four', $payment->last4);
    }

}
