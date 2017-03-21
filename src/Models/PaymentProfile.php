<?php

namespace TheLHC\CustomerPayment\Models;

use Illuminate\Database\Eloquent\Model;
use TheLHC\CustomerPayment\Traits\Paymentable;
use Illuminate\Support\Facades\DB;

class PaymentProfile extends Model
{
    use Paymentable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_profiles';

    /**
     * User timestamps columns
     *
     * @var boolean
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        /**
         * Creating callback
         *
         * @var App\Models\PaymentProfile
         * @return mixed
         */
        static::creating(function ($paymentProfile) {
            if (empty($paymentProfile->customer_profile_id)) {
                $params = $paymentProfile->getAttributes();
                $paymentProfile->createPaymentProfile($params);
            }
        });

        /**
         * Updating callback
         *
         * @var App\Models\PaymentProfile
         * @return mixed
         */
        static::updating(function ($paymentProfile) {
            $params = $paymentProfile->getAttributes();
            $paymentProfile->updatePaymentProfile($params);
        });

        /**
         * Deleting callback
         *
         * @var App\Models\PaymentProfile
         * @return mixed
         */
        static::deleting(function ($paymentProfile) {
            $paymentProfile->deletePaymentProfile();
        });
    }

    /**
     * User relationship (belongs-to)
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        $userClass = config()->get('customer_payment.model');

        return $this->belongsTo($userClass, 'user_id');
    }

    /**
     * Retrieve the customer profile identifier value (Model accessor)
     *
     * @param  null $value
     * @return string
     */
    public function getCustomerProfileIdAttribute($value)
    {
        return $this->user->customer_profile_id;
    }
}
