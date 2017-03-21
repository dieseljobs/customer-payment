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

    public function getPaymentProfileIdAttribute($value)
    {
        $col = $this->getPaymentProfileIdColumn();

        return $this->$col;
    }

    public function createPaymentProfile($params)
    {
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

    public function getFull()
    {
        $payment = PaymentProcessor::findPaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id
        );

        return $payment;
    }

    public function updatePaymentProfile($params)
    {
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

    public function deletePaymentProfile()
    {
        $deleted = PaymentProcessor::deletePaymentProfile(
            $this->customer_profile_id,
            $this->payment_profile_id
        );

        return $deleted;
    }

    public function verifyFilledAttributes()
    {
        // get fillable from hard table columns
        $schema = app('db')->connection()->getSchemaBuilder();
        $tableCols = $schema->getColumnListing($this->table);
        $fillable = $tableCols;
        if (in_array('id', $fillable)) {
            unset($fillable[array_search('id', $fillable)]);
        }

        // cross-examine fillable attributes with currently set attributes
        $keep = array_intersect_key(
            $this->getAttributes(),
            array_flip($fillable)
        );

        // force set valid attributes
        $this->attributes = $keep;
    }

}
