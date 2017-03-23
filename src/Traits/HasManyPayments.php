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

    /**
     * Get payment profiles directly from payment processor
     *
     * @return Illuminate\Database\Collection
     */
    public function getPaymentProfiles()
    {
        return PaymentProcessor::getPaymentProfiles($this->customer_profile_id);
    }

    /**
     * Get 'Full' payment profiles
     * Retrieves payment profiles from payment processor and concatenates into
     * payment_profiles eloquent collection
     *
     * @return Illuminate\Database\Collection
     */
    public function full_payment_profiles()
    {
        // get eloquent relation
        $model_payments = $this->payment_profiles;
        if (! $model_payments) return [];

        // get payment profiles from processor
        $processor_payments = $this->getPaymentProfiles();
        $key = PaymentProcessor::getPaymentProfileKey();

        // find and attach each profile from payment processor to model relation
        foreach($model_payments as $model_payment) {
            $find = $processor_payments->where(
                $key,
                $model_payment->payment_profile_id
            )->first();
            $model_payment->details = $find;
        }

        return $model_payments;
    }
}
