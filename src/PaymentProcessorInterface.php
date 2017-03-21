<?php

namespace TheLHC\CustomerPayment;

interface PaymentProcessorInterface
{
    /**
     * The customer profile identifier passed in response objects
     *
     * @return string
     */
    public function getCustomerProfileKey();

    /**
     * The column name to store/retrieve customer profile ids
     *
     * @return string
     */
    public function getCustomerIdColumn();

    /**
     * Create customer profile from params
     *
     * @param  array $params
     * @return mixed
     */
    public function createCustomerProfile($params);

    /**
     * Find a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function findCustomerProfile($id);

    /**
     * Update a customer profile
     *
     * @param  string $id
     * @param  array $params
     * @return mixed
     */
    public function updateCustomerProfile($id, $params);

    /**
     * Delete a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function deleteCustomerProfile($id);

    public function getPaymentProfileKey();

    public function getPaymentIdColumn();

    /**
     * Create customer payment profile from params
     *
     * @param  string  $customerId
     * @param  array $params
     * @return mixed
     */
    public function createPaymentProfile($customerId, $params);

    public function findPaymentProfile($customerId, $paymentId);

    public function updatePaymentProfile($customerId, $paymentId, $params);

    public function deletePaymentProfile($customerId, $paymentId);
}
