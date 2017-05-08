<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use TheLHC\CustomerPayment\Tests\User;
use TheLHC\CustomerPayment\PaymentProfile;

class HasManyPaymentsTest extends TestCase
{

    public $stripe_id = "cus_AQuKtXQyM6Byyh";
    public $stripe_card_id = "card_1AHDEKJfQv1xXyoLVXbkfnCv";

    public function testCanCatchCreatePaymentProfileErrors()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $attrs = [
            'number'    => '4242424',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $paymentProfile = new PaymentProfile($attrs);

        $this->assertEquals(false, $user->payment_profiles()->save($paymentProfile));
        $this->assertEquals(true, is_array($paymentProfile->getPaymentProfileErrors()));
    }

    public function testCanCreatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $attrs = [
            'number'    => '4242424242424242',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $paymentProfile = new PaymentProfile($attrs);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertEquals(true, !empty($paymentProfile->stripe_card_id));
        //dd($paymentProfile->stripe_card_id);
    }

    public function testCanFindPaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_id' => $this->stripe_card_id]);
        $user->payment_profiles()->save($paymentProfile);
        $fullPayment = $paymentProfile->getFull();
        $this->assertEquals($paymentProfile->stripe_card_id, $fullPayment->id);
    }

    public function testCanCatchUpdatePaymentProfileErrors()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_id' => 'wrong']);
        $user->payment_profiles()->save($paymentProfile);

        $attrs = [
            'foo' => 'bar'
        ];

        $this->assertEquals(false, $paymentProfile->update($attrs));
        $this->assertEquals(true, is_array($paymentProfile->getPaymentProfileErrors()));
    }

    public function testCanUpdatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_id' => $this->stripe_card_id]);
        $user->payment_profiles()->save($paymentProfile);

        $attrs = [
            'address_line1' => '747 MAIN ST',
            'address_city' => 'WESTBROOK',
            'address_state' => 'ME',
            'address_zip' => '04092',
            'metadata' => [
                'company' => "weaseljobs"
            ]
        ];
        $this->assertTrue($paymentProfile->update($attrs));
    }

    public function testItCatchesDeletePaymentProfileErrors()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_id' => 'foobar']);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertEquals(false, $paymentProfile->delete());
        $this->assertEquals(true, is_array($paymentProfile->getPaymentProfileErrors()));
    }

    public function testItCanDeletePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'name' => 'Aaron kaz',
            'stripe_id' => $this->stripe_id
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_id' => $this->stripe_card_id]);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertTrue($paymentProfile->delete());
    }

}


/*
$payment = [
  "id" => "card_19zr8pJfQv1xXyoLyxInWrGo",
  "object" => "card",
  "address_city" => null,
  "address_country" => null,
  "address_line1" => null,
  "address_line1_check" => null,
  "address_line2" => null,
  "address_state" => null,
  "address_zip" => null,
  "address_zip_check" => null,
  "brand" => "Visa",
  "country" => "US",
  "customer" => "cus_AKAtaFldeKfE7x",
  "cvc_check" => "pass",
  "dynamic_last4" => null,
  "exp_month" => 10,
  "exp_year" => 2020,
  "fingerprint" => "fqGBFGcbu1bVmxZI",
  "funding" => "credit",
  "last4" => "4242",
  "metadata" => [],
  "name" => null,
  "tokenization_method" => null,
];
$payment = (object)$payment;
*/
