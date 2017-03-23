<?php

namespace TheLHC\CustomerPayment\Traits;
use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait Chargeable
{
    /**
     * Charge payment profile
     *
     * @param  array $params
     * @return mixed
     */
    public function charge($params)
    {
        $charge = PaymentProcessor::chargePaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id,
            $params
        );

        return $charge;
    }
}
