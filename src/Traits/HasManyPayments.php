<?php

namespace TheLHC\CustomerPayment\Traits;
use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait HasManyPayments
{
    /**
     * PaymentProfile relation (one-to-many)
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function payment_profiles()
    {
        return $this->hasMany('TheLHC\CustomerPayment\Models\PaymentProfile', 'user_id');
    }
}
