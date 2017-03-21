<?php

namespace TheLHC\CustomerPayment;

use Cartalyst\Stripe\Stripe;
use Cartalyst\Stripe\Exception\NotFoundException;

class StripePaymentProvider implements PaymentProcessorInterface
{
    /**
     * Configuration passed from CustomerPaymentManager
     *
     * @var array
     */
    private $config;

    /**
     * Stripe API connection
     *
     * @var Cartalyst\Stripe\Stripe
     */
    private $stripe;

    /**
     * Permissible create/update customer profile parameters
     *
     * @var array
     */
    private $customerParams = [
        'account_balance',
        'coupon',
        'email',
        'metadata',
        'source'
    ];

    /**
     * Permissible create/update payment profile parameters
     *
     * @var array
     */
    private $paymentParams = [
        'number',
        'exp_month',
        'exp_year',
        'cvc',
        'address_city',
        'address_line1',
        'address_line2',
        'address_state',
        'address_zip',
        'name',
        'metadata'
    ];

    /**
     * Setup new instance with configuration values
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->stripe = Stripe::make($config['api_key']);
    }

    /**
     * The customer profile identifier passed in response objects
     *
     * @return string
     */
    public function getCustomerProfileKey()
    {
        return 'id';
    }

    /**
     * The column name to store/retrieve customer profile ids
     *
     * @return string
     */
    public function getCustomerIdColumn()
    {
        if (isset($this->config['customer_id_column'])) {
            return $this->config['customer_id_column'];
        }

        return 'stripe_id';
    }

    /**
     * The payment profile indentifier passed in response objects
     *
     * @return string
     */
    public function getPaymentProfileKey()
    {
        return 'id';
    }

    /**
     * The table column name to store/retrieve payment profile ids
     *
     * @return string
     */
    public function getPaymentIdColumn()
    {
        if (isset($this->config['payment_id_column'])) {
            return $this->config['payment_id_column'];
        }

        return 'stripe_card_id';
    }

    /**
     * Create customer profile from params
     *
     * @param  array $params
     * @return mixed
     */
    public function createCustomerProfile($params)
    {
        $sendParams = $this->verifyParams($params, $this->customerParams);
        $customer = $this->stripe->customers()->create($sendParams);

        return (object)$customer;
    }

    /**
     * Find a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function findCustomerProfile($id)
    {
        $customer = $this->stripe->customers()->find($id);

        return (object)$customer;
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
        $sendParams = $this->verifyParams($params, $this->customerParams);
        $customer = $this->stripe->customers()->update($id, $sendParams);

        return (object)$customer;
    }

    /**
     * Delete a customer profile
     *
     * @param  string $id
     * @return mixed
     */
    public function deleteCustomerProfile($id)
    {
        $response = $this->stripe->customers()->delete($id);

        return (isset($response['deleted']) and $response['deleted']);
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
        $sendParams = $this->verifyParams($params, $this->paymentParams);
        $card = $this->stripe->cards()->create($customerId, $sendParams);

        return (object)$card;
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
        $card = $this->stripe->cards()->find($customerId, $paymentId);

        return (object)$card;
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
        $sendParams = $this->verifyParams($params, $this->paymentParams);
        $card = $this->stripe->cards()->update($customerId, $paymentId, $sendParams);

        return (object)$card;
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
        $response = $this->stripe->cards()->delete($customerId, $paymentId);

        return (isset($response['deleted']) and $response['deleted']);
    }

    /**
     * Verify and normalize incoming parameters
     *
     * @param  array $params
     * @param  array $checkParams   parameters to verify against
     * @return array
     */
    private function verifyParams($params, $checkParams)
    {
        $sendParams = [];
        foreach($params as $key => $val) {
            if (in_array($key, $checkParams)) {
                $sendParams[$key] = $val;
            }
        }

        return $sendParams;
    }
}
