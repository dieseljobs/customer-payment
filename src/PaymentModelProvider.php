<?php

namespace TheLHC\CustomerPayment;

use Illuminate\Database\Eloquent\Model;
use TheLHC\CustomerPayment\Traits\Paymentable;
use TheLHC\CustomerPayment\Traits\Chargeable;

class PaymentModelProvider extends Model
{
    use Paymentable, Chargeable;

    protected static function boot()
    {
        parent::boot();

        /**
         * Creating callback
         *
         * @var TheLHC\CustomerPayment\PaymentModelProvider
         * @return mixed
         */
        static::creating(function ($model) {
            if (empty($model->payment_profile_id)) {
                $createPaymentProfile = $model->createPaymentProfile();
                if ( !$createPaymentProfile) return false;
            }
        });

        /**
         * Updating callback
         *
         * @var TheLHC\CustomerPayment\PaymentModelProvider
         * @return mixed
         */
        static::updating(function ($model) {
            if (!empty($model->payment_profile_id)) {
                $updatePaymentProfile = $model->updatePaymentProfile();
                if ( !$updatePaymentProfile) return false;
            }
        });

        /**
         * Deleting callback
         *
         * @var TheLHC\CustomerPayment\PaymentModelProvider
         * @return mixed
         */
        static::deleting(function ($model) {
            $deletePaymentProfile = $model->deletePaymentProfile();
            if ( !$deletePaymentProfile) return false;
        });
    }

    /**
     * Overload Illuminate\Database\Eloquent\Model registerModelEvent
     * Register a model event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     * @param  int  $priority
     * @return void
     */
    protected static function registerModelEvent($event, $callback, $priority = 0)
    {
        if (isset(static::$dispatcher)) {

            //$name = static::class;
            $name = get_called_class();

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback, $priority);
        }
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
