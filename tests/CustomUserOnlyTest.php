<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use TheLHC\CustomerPayment\Tests\CustomUserNoRelation as User;

class CustomUserOnlyTest extends TestCase
{

    public $stripe_id = "cus_APiUV6EZFnvSaC";
    public $stripe_card_id = "card_1A4vCSJfQv1xXyoLraMqpAKu";

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set(
            'customer_payment.model',
            \TheLHC\CustomerPayment\Tests\CustomUserNoRelation::class
        );
    }

    public function testCustomUserNoRelationCanCreateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
        ]);

        $user->createCustomerProfile();
        $this->assertEquals(true, !empty($user->stripe_acct));
    }

    public function testCustomUserNoRelationCanRetrieveCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id
        ]);
        $profile = $user->customerProfile();
        $this->assertEquals($user->stripe_id, $profile->id);
    }

    public function testCustomUserNoRelationCanUpdateCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel #' . rand(1,100),
            'stripe_id' => $this->stripe_id
        ]);
        $profile = $user->updateCustomerProfile();
        $this->assertEquals(
            $user->name, $profile->description
        );
    }

    public function testCustomUserNoRelationCanDeleteCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id
        ]);
        $profile = $user->deleteCustomerProfile();
        $this->assertEquals(true, empty($user->stripe_acct));
    }

    public function testCustomUserNoRelationCanCatchCreatePaymentProfileErrors()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id
        ]);
        $attrs = [
            'number'    => '4242424',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $this->assertEquals(false, $user->storePayment($attrs));
        $this->assertEquals(true, is_array($user->getPaymentProfileErrors()));
    }

    public function testCustomUserNoRelationCanCreatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id
        ]);
        $attrs = [
            'number'    => '4242424242424242',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $user->storePayment($attrs);
        $this->assertEquals(true, !empty($user->stripe_card_id));
    }

    public function testCustomUserNoRelationCanCreatePaymentProfileWithoutCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ thelhc'
        ]);
        $attrs = [
            'number'    => '4242424242424242',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $user->storePayment($attrs);
        $this->assertEquals(true, !empty($user->stripe_id));
        $this->assertEquals(true, !empty($user->stripe_card_id));
    }

    public function testCustomUserNoRelationCanFindPaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id,
            'stripe_card_id' => $this->stripe_card_id,
        ]);

        $payment = $user->getPayment();
        $this->assertEquals($user->stripe_card_id, $payment->id);
    }

    public function testCustomUserNoRelationCanUpdatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id,
            'stripe_card_id' => $this->stripe_card_id,
        ]);
        $attrs = [
            'address_line1' => '747 MAIN ST',
            'address_city' => 'WESTBROOK',
            'address_state' => 'ME',
            'address_zip' => '04092',
            'metadata' => [
                'company' => "weaseljobs"
            ]
        ];
        $user->updatePayment($attrs);
        $payment = $user->getPayment();
        $this->assertEquals($attrs['address_line1'], $payment->address_line1);
    }

    public function testCustomUserNoRelationCanDeletePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id,
            'stripe_card_id' => $this->stripe_card_id,
        ]);
        $this->assertTrue($user->deletePayment());
        $this->assertTrue(is_null($user->stripe_card_id));
    }

    public function testCustomUserNoRelationCanChargePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron Kaz @ diesel',
            'stripe_id' => $this->stripe_id,
            'stripe_card_id' => $this->stripe_card_id,
        ]);

        $attrs = [
            'amount' => 100.00,
            'description' => 'Test charge',
            'statement_descriptor' => 'Test user charge',
            'metadata' => [
                'invoice_id' => "1111"
            ]
        ];

        $charge = $user->charge($attrs);
        $this->assertEquals(true, !empty($charge->id));
    }

}
