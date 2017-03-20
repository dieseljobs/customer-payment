<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomerPaymentTest extends TestCase
{

    public $stripe_id = "cus_AKAkDuF6w8EaWp";

    public function testCanCreateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
        ]);
        $user->createCustomerProfile();
        $this->assertEquals(true, !empty($user->stripe_id));
    }

    public function testCanRetrieveCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $profile = $user->customerProfile();
        $this->assertEquals($user->stripe_id, $profile->id);
    }

    public function testCanUpdateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $user->description = "foobar";
        $profile = $user->updateCustomerProfile();
        $this->assertEquals($user->description, $profile->description);
    }

    public function testItCanDeleteCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $profile = $user->deleteCustomerProfile();
        $this->assertEquals(true, empty($user->stripe_id));
    }
}

class User extends Eloquent
{
    use TheLHC\CustomerPayment\Profileable;

    public $timestamps = false;

    public $customerProfileIdColumn = "foobar_id";
}
