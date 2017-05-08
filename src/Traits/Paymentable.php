<?php

namespace TheLHC\CustomerPayment\Traits;

use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait Paymentable
{
    /**
     * Errors from ErrorBag if returned
     *
     * @return array
     */
    protected $paymentProfileErrors;

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
     * @param  array $params
     * @return array
     */
    public function paymentProfileParams($params = null)
    {
        // if params are passed to the method, just pass through and
        // reserve special logic for overloaded methods
        if ($params) {
            return $params;
        } else {
            return $this->toArray();
        }
    }

    /**
     * Create a payment profile with payment processor
     *
     * @param  array $params
     * @return void
     */
    public function createPaymentProfile($params = null)
    {
        // create customer profile if not setup
        if (! $this->customer_profile_id) {
            if (method_exists($this, "createCustomerProfile")) {
                $this->createCustomerProfile();
            } else {
                $this->user->createCustomerProfile();
            }
        }

        $params = $this->paymentProfileParams($params);
        $payment = PaymentProcessor::createPaymentProfile(
            $this->customer_profile_id,
            $params
        );

        // catch errors
        if (is_object($payment) and get_class($payment) === "TheLHC\CustomerPayment\ErrorBag") {
            $this->paymentProfileErrors = $payment->getErrors();
            return false;
        }

        $paymentKey = PaymentProcessor::getPaymentProfileKey();
        $col = $this->getPaymentProfileIdColumn();
        $this->setAttribute($col, $payment->$paymentKey);

        if (method_exists($this, 'setPaymentColumns')) {
            $this->setPaymentColumns($payment);
        }

        $this->verifyFilledAttributes();

        return true;
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
    public function updatePaymentProfile($params = null)
    {
        $params = $this->paymentProfileParams($params);
        $payment = PaymentProcessor::updatePaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id,
            $params
        );

        // catch errors
        if (is_object($payment) and get_class($payment) === "TheLHC\CustomerPayment\ErrorBag") {
            $this->paymentProfileErrors = $payment->getErrors();
            return false;
        }

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

        // catch errors
        if (is_object($deleted) and get_class($deleted) === "TheLHC\CustomerPayment\ErrorBag") {
            $this->paymentProfileErrors = $deleted->getErrors();
            return false;
        }

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

         // make sure we don't pull out id
         $fillable = array_merge($this->getFillable(), ['id']);

         // cross-examine fillable attributes with currently set attributes
         $keep = array_intersect_key(
             $this->getAttributes(),
             array_flip($fillable)
         );

         // force set valid attributes
         $this->setRawAttributes($keep);
     }

     /**
      * Get errors returned from payment processor call exception
      *
      * @return array
      */
     public function getPaymentProfileErrors()
     {
         return $this->paymentProfileErrors;
     }

}
