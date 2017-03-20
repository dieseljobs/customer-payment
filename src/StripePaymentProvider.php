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
        'account_balance' => [
            'required' => false
        ],
        'coupon' => [
            'required' => false
        ],
        'description' => [
            'required' => false
        ],
        'email' => [
            'required' => false
        ],
        'metadata' => [
            'required' => false
        ],
        'source' => [
            'required' => false
        ]
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
     * Create customer profile from params
     *
     * @param  array $params
     * @return mixed
     */
    public function createCustomerProfile($params)
    {
        $sendParams = $this->validateCustomerParams($params);
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
        $sendParams = $this->validateCustomerParams($params);
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
     * Validate and normalize incoming create/update customer profile parameters
     *
     * @param  array $params
     * @return array
     */
    private function validateCustomerParams($params)
    {
        $sendParams = [];
        foreach($params as $key => $val) {
            if (isset($this->customerParams[$key])) {
                $sendParams[$key] = $val;
            }
        }

        return $sendParams;
    }
}
