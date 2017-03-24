<?php

namespace TheLHC\CustomerPayment\Traits;
use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait Paymentable
{
    /**
     * Get the column name where customer profile identifier is stored
     *
     * @return string
     */
    public function getPaymentProfileIdColumn()
    {
        if (property_exists($this, "paymentProfileIdColumn")) {
            return $this->paymentProfileIdColumn;
        }

        return PaymentProcessor::getPaymentIdColumn();
    }

    /**
     * Get the payment profile identifier value accessor
     *
     * @param  null $value
     * @return string
     */
    public function getPaymentProfileIdAttribute($value)
    {
        $col = $this->getPaymentProfileIdColumn();

        return $this->$col;
    }

    /**
     * Resolve default params to send to create/update payment profile
     * request
     *
     * @return array
     */
    public function paymentProfileParams()
    {
        return $this->toArray();
    }

    /**
     * Create a payment profile with payment processor
     *
     * @param  array $params
     * @return void
     */
    public function createPaymentProfile()
    {
        // create customer profile if not setup
        if (! $this->customer_profile_id) {
            if (method_exists($this, "createCustomerProfile")) {
                $this->createCustomerProfile();
            } else {
                $this->user->createCustomerProfile();
            }
        }

        $params = $this->paymentProfileParams();
        $payment = PaymentProcessor::createPaymentProfile(
            $this->customer_profile_id,
            $params
        );

        $paymentKey = PaymentProcessor::getPaymentProfileKey();
        $col = $this->getPaymentProfileIdColumn();
        $this->$col = $payment->$paymentKey;

        if (method_exists($this, 'setPaymentColumns')) {
            $this->setPaymentColumns($payment);
        }

        $this->verifyFilledAttributes();
    }

    /**
     * Retrieve payment profile details from payment processor
     *
     * @return mixed
     */
    public function getFull()
    {
        $payment = PaymentProcessor::findPaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id
        );

        return $payment;
    }

    /**
     * Update payment profile with payment processor
     *
     * @param  array $params
     * @return mixed
     */
    public function updatePaymentProfile()
    {
        $params = $this->paymentProfileParams();
        $payment = PaymentProcessor::updatePaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id,
            $params
        );

        if (method_exists($this, 'setPaymentColumns')) {
            $this->setPaymentColumns($payment);
        }

        $this->verifyFilledAttributes();
    }

    /**
     * Delete payment profile at payment processor
     *
     * @return boolean
     */
    public function deletePaymentProfile()
    {
        $deleted = PaymentProcessor::deletePaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id
        );

        return $deleted;
    }

    /**
     * Ensure model attributes match only fillable table columns at save and
     * virtual attributes are stripped after successful create/update payment
     * profile call
     *
     * @return void
     */
    public function verifyFilledAttributes()
    {
        if (empty($this->getFillable())) {
            // get fillable from hard table columns
            $schema = app('db')->connection()->getSchemaBuilder();
            $fillable = $schema->getColumnListing($this->table);
            if (in_array('id', $fillable)) {
                unset($fillable[array_search('id', $fillable)]);
            }
            $this->fillable($fillable);
        }

        // cross-examine fillable attributes with currently set attributes
        $keep = array_intersect_key(
            $this->getAttributes(),
            array_flip($this->getFillable())
        );

        // force set valid attributes
        $this->setRawAttributes($keep);
    }

}
