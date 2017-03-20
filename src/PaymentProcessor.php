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
     * The column name to store/retrieve customer profile ids
     *
     * @return string
     */
    public function getCustomerIdColumn()
    {
        return $this->provider->getCustomerIdColumn();
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
}
