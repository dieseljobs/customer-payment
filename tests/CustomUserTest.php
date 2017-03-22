<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use TheLHC\CustomerPayment\Tests\CustomUser as User;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomUserTest extends TestCase
{

    public $stripe_acct = "cus_AKqPLr4lGP5Xkc";

    public function testCustomUserCanCreateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'UEG'
        ]);
        $user->createCustomerProfile();
        $this->assertEquals(true, !empty($user->stripe_acct));
    }


    public function testCustomUserCanRetrieveCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'UEG',
            'stripe_acct' => $this->stripe_acct
        ]);
        $profile = $user->customerProfile();
        $this->assertEquals($user->stripe_acct, $profile->id);
    }


    public function testCustomUserCanUpdateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $profile = $user->updateCustomerProfile();
        $this->assertEquals(
            "{$user->fname} {$user->lname} ({$user->company})", $profile->description
        );
    }

    public function testCustomUserCanDeleteCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $profile = $user->deleteCustomerProfile();
        $this->assertEquals(true, empty($user->stripe_acct));
    }

}
