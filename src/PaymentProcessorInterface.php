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
     * The table column name to store/retrieve customer profile ids
     *
     * @return string
     */
    public function getCustomerIdColumn();

    /**
     * The payment profile indentifier passed in response objects
     *
     * @return string
     */
    public function getPaymentProfileKey();

    /**
     * The table column name to store/retrieve payment profile ids
     *
     * @return string
     */
    public function getPaymentIdColumn();

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

    /**
     * Create customer payment profile from params
     *
     * @param  string  $customerId
     * @param  array $params
     * @return mixed
     */
    public function createPaymentProfile($customerId, $params);

    /**
     * Find a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @return mixed
     */
    public function findPaymentProfile($customerId, $paymentId);

    /**
     * Update a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @param  array $params
     * @return mixed
     */
    public function updatePaymentProfile($customerId, $paymentId, $params);

    /**
     * Delete a customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @return boolean
     */
    public function deletePaymentProfile($customerId, $paymentId);

    /**
     * Retrieve all customer payment profiles from processor
     *
     * @param  string $customerId
     * @return Illuminate\Support\Collection
     */
    public function getPaymentProfiles($customerId);

    /**
     * Charge customer payment profile
     *
     * @param  string $customerId
     * @param  string $paymentId
     * @param  array $params
     * @return mixed
     */
    public function chargePaymentProfile($customerId, $paymentId, $params);

    /**
     * Create a charge
     *
     * @param  array $params
     * @return mixed
     */
    public function createCharge($params);

    /**
     * Retrieve a charge
     *
     * @param  string $chargeId
     * @return mixed
     */
    public function findCharge($chargeId);

    /**
     * Update a charge
     *
     * @param  string $chargeId
     * @param  array $params
     * @return mixed
     */
    public function updateCharge($chargeId, $params);

    /**
     * Capture a charge
     *
     * @param  string $chargeId
     * @return mixed
     */
    public function captureCharge($chargeId, $amount = null, array $params = []);
}
