<?php

namespace TheLHC\CustomerPayment\Traits;
use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait HasOnePayment
{
    use Paymentable;

    /**
     * Alias to create new payment profile
     *
     * @param  array $params
     * @return void
     */
    public function storePayment($params)
    {
        $this->createPaymentProfile($params);
        $this->save();
    }

    /**
     * Alias to get full payment profile
     *
     * @return mixed
     */
    public function getPayment()
    {
        return $this->getFull();
    }

    /**
     * Alias to update payment profile
     *
     * @param  array $params
     * @return void
     */
    public function updatePayment($params)
    {
        $this->updatePaymentProfile($params);
        $this->save();
    }

    /**
     * Alias to delete payment profile
     *
     * @return boolean 
     */
    public function deletePayment()
    {
        $deleted = $this->deletePaymentProfile();

        if ($deleted) {
            $col = $this->getPaymentProfileIdColumn();
            $this->update([$col => null]);
        }

        return $deleted;
    }
}
