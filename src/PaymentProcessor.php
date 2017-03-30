<?php

namespace TheLHC\CustomerPayment;

class PaymentProcessor implements PaymentProcessorInterface
{
    /**
     * Provider instance implementing PaymentProcessorInterface
     *
     * @var PaymentProcessorInterface
     */
    protected $provider;

    /**
     * Initialize new instance with implementation of PaymentProcessorInterface
     *
     * @param PaymentProcessorInterface $provider
     */
    public function __construct(PaymentProcessorInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * The customer profile identifier passed in response objects
     *
     * @return string
     */
    public function getCustomerProfileKey()
    {
        return $this->provider->getCustomerProfileKey();
    }

    /**
     * The table column name to store/retrieve customer profile ids
     *
     * @return string
     */
    public function getCustomerIdColumn()
    {
        return $this->provider->getCustomerIdColumn();
    }

    /**
     * The payment profile indentifier passed in response objects
     *
     * @return string
     */
    public function getPaymentProfileKey()
    {
        return $this->provider->getPaymentProfileKey();
    }

    /**
     * The table column name to store/retrieve payment profile ids
     *
     * @return string
     */
    public function getPaymentIdColumn()
    {
        return $this->provider->getPaymentIdColumn();
    }

    /**
     * Create customer profile from params
     *
     * @param  array $params
     * @return mixed
     */
    public function createCustomerProfile($params)
    {
        return $this->provider->createCustomerProfile($params);
    }

    /**
     * Find a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function findCustomerProfile($id)
    {
        return $this->provider->findCustomerProfile($id);
    }

    /**
     * Update a customer profile
     *
     * @param  string $id
     * @param  array $params
     * @return mixed
     */
    public function updateCustomerProfile($id, $params)
    {
        return $this->provider->updateCustomerProfile($id, $params);
    }

    /**
     * Delete a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function deleteCustomerProfile($id)
    {
        return $this->provider->deleteCustomerProfile($id);
    }

    /**
     * Create customer payment profile from params
     *
     * @param  string  $customerId
     * @param  array $params
     * @return mixed
     */
    public function createPaymentProfile($customerId, $params)
    {
        return $this->provider->createPaymentProfile($customerId, $params);
    }

    /**
     * Find a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @return mixed
     */
    public function findPaymentProfile($customerId, $paymentId)
    {
        return $this->provider->findPaymentProfile($customerId, $paymentId);
    }

    /**
     * Update a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @param  array $params
     * @return mixed
     */
    public function updatePaymentProfile($customerId, $paymentId, $params)
    {
        return $this->provider->updatePaymentProfile(
            $customerId,
            $paymentId,
            $params
        );
    }

    /**
     * Delete a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @return boolean
     */
    public function deletePaymentProfile($customerId, $paymentId)
    {
        return $this->provider->deletePaymentProfile($customerId, $paymentId);
    }

    /**
     * Retrieve all customer payment profiles from processor
     *
     * @param  string $customerId
     * @return Illuminate\Support\Collection
     */
    public function getPaymentProfiles($customerId)
    {
        return $this->provider->getPaymentProfiles($customerId);
    }

    /**
     * Charge customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @param  array $params
     * @return mixed
     */
    public function chargePaymentProfile($customerId, $paymentId, $params)
    {
        return $this->provider->chargePaymentProfile($customerId, $paymentId, $params);
    }

    /**
     * Create a charge
     *
     * @param  array $params
     * @return mixed
     */
    public function createCharge($params)
    {
        return $this->provider->createCharge($params);
    }

    /**
     * Retrieve a charge
     *
     * @param  string $chargeId
     * @return mixed
     */
    public function findCharge($chargeId)
    {
        return $this->provider->findCharge($chargeId);
    }

    /**
     * Update a charge
     *
     * @param  string $chargeId
     * @param  array $params
     * @return mixed
     */
    public function updateCharge($chargeId, $params)
    {
        return $this->provider->updateCharge($chargeId, $params);
    }

    /**
     * Capture a charge
     *
     * @param  string $chargeId
     * @param  integer $amount
     * @param  array $params
     * @return mixed
     */
    public function captureCharge($chargeId, $amount = null, array $params = [])
    {
        return $this->provider->captureCharge($chargeId, $amount, $params);
    }

}
