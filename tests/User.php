<?php

namespace TheLHC\CustomerPayment\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    use \TheLHC\CustomerPayment\Traits\Profileable;
    use \TheLHC\CustomerPayment\Traits\HasManyPayments;

    public $timestamps = false;

}
