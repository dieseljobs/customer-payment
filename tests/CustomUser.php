<?php

namespace TheLHC\CustomerPayment\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomUser extends Eloquent
{
    use \TheLHC\CustomerPayment\Traits\Profileable;
    use \TheLHC\CustomerPayment\Traits\HasManyPayments;

    public $timestamps = false;

    public $customerProfileIdColumn = 'stripe_acct';

    protected $fillable = [
        'email',
        'fname',
        'lname',
        'company',
        'stripe_acct'
    ];

    public function payment_profiles()
    {
        return $this->hasMany('TheLHC\CustomerPayment\Tests\CustomPaymentProfile', 'user_id');
    }

    /**
     * Resolve default user params to send to create/update customer profile
     * request
     *
     * @return array
     */
    public function customerProfileParams()
    {
        $sendParams = [
            'email' => $this->email,
            'description' => "{$this->fname} {$this->lname} ({$this->company})",
            'metadata' => [
                'user_id' => $this->id
            ]
        ];

        return $sendParams;
    }

}
