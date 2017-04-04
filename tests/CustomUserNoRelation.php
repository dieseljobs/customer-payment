<?php

namespace TheLHC\CustomerPayment\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomUserNoRelation extends Eloquent
{
    use \TheLHC\CustomerPayment\Traits\Profileable;
    use \TheLHC\CustomerPayment\Traits\HasOnePayment;
    use \TheLHC\CustomerPayment\Traits\Chargeable;

    public $table = 'custom_users_two';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'name',
        'stripe_id',
        'stripe_card_id'
    ];

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
            'description' => $this->name,
            'metadata' => [
                'name' => $this->name,
                'user_id' => $this->id
            ]
        ];

        return $sendParams;
    }

}
