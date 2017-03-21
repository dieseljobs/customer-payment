<?php

namespace TheLHC\CustomerPayment\Traits;

use TheLHC\CustomerPayment\Facades\PaymentProcessor;

trait Profileable
{

    /**
     * Get the column name where customer profile identifier is stored
     *
     * @return string
     */
    public function getCustomerProfileIdColumn()
    {
        if (property_exists($this, "customerProfileIdColumn")) {
            return $this->customerProfileIdColumn;
        }

        return PaymentProcessor::getCustomerIdColumn();
    }

    /**
     * Retrieve the customer profile identifier value (Model accessor)
     *
     * @param  null $value
     * @return string
     */
    public function getCustomerProfileIdAttribute($value)
    {
        $col = $this->getCustomerProfileIdColumn();

        return $this->$col;
    }

    /**
     * Determine if model has attached customer profile accessor
     *
     * @param  null $value
     * @return boolean
     */
    public function getHasCustomerProfileAttribute($value)
    {
        $col = $this->getCustomerProfileIdColumn();

        return !empty($this->$col);
    }

    /**
     * Resolve default user params to send to create/update customer profile
     * request
     *
     * @return array
     */
    public function customerProfileParams()
    {
        return $this->toArray();
    }

    /**
     * Create customer profile with payment processor
     * Adds customer profile id value on success
     *
     * @return stdClass
     */
    public function createCustomerProfile()
    {
        $params = $this->customerProfileParams();
        $profile = PaymentProcessor::createCustomerProfile($params);
        $profileKey = PaymentProcessor::getCustomerProfileKey();
        $col = $this->getCustomerProfileIdColumn();
        $attrs[$col] = $profile->$profileKey;
        $this->update($attrs);

        return $profile;
    }

    /**
     * Retrieve customer profile from payment processor
     *
     * @return stdClass
     */
    public function customerProfile()
    {
        $profile = PaymentProcessor::findCustomerProfile(
            $this->customer_profile_id
        );

        return $profile;
    }

    /**
     * Update customer profile with payment processor
     * If params are not passed explicitly, params are gathered from current
     * model values
     *
     * @param  array   $params
     * @return stdClass
     */
    public function updateCustomerProfile($params = null)
    {
        $params = ($params) ? $params : $this->customerProfileParams();
        $profile = PaymentProcessor::updateCustomerProfile(
            $this->customer_profile_id,
            $params
        );

        return $profile;
    }

    /**
     * Delete customer profile with payment processor
     * Removes customer id column value on success
     *
     * @return boolean
     */
    public function deleteCustomerProfile()
    {
        $deleted = PaymentProcessor::deleteCustomerProfile(
            $this->customer_profile_id
        );

        if ($deleted) {
            $col = $this->getCustomerProfileIdColumn();
            $attrs[$col] = null;
            $this->update($attrs);
        }

        return $deleted;
    }
}
