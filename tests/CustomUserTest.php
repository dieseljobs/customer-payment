<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use TheLHC\CustomerPayment\Tests\CustomUser as User;
use TheLHC\CustomerPayment\Tests\CustomPaymentProfile as PaymentProfile;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomUserTest extends TestCase
{

    public $stripe_acct = "cus_AKqPLr4lGP5Xkc";
    public $stripe_card_acct = "card_1A0GbVJfQv1xXyoLWLC0TOHn";

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set(
            'customer_payment.model',
            \TheLHC\CustomerPayment\Tests\CustomUser::class
        );
    }

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

    public function testCustomUserCanCreatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $attrs = [
            'number'    => '4242424242424242',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $paymentProfile = new PaymentProfile($attrs);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertEquals(true, !empty($paymentProfile->stripe_card_acct));
    }

    public function testCustomUserCanCreatePaymentProfileWithoutCustomerProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com'
        ]);
        $attrs = [
            'number'    => '4242424242424242',
            'exp_month' => 10,
            'cvc'       => 314,
            'exp_year'  => 2020,
        ];
        $paymentProfile = new PaymentProfile($attrs);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertEquals(true, !empty($paymentProfile->stripe_card_acct));
    }

    public function testCustomUserCanFindPaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
        $user->payment_profiles()->save($paymentProfile);
        $fullPayment = $paymentProfile->getFull();
        $this->assertEquals($paymentProfile->stripe_card_acct, $fullPayment->id);
    }

    public function testCustomUserCanUpdatePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
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

    public function testCustomUserCanDeletePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
        $user->payment_profiles()->save($paymentProfile);
        $this->assertTrue($paymentProfile->delete());
    }

    public function testCustomUserCanRetrieveAllPaymentProfiles()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
        $user->payment_profiles()->save($paymentProfile);
        $payments = $user->getPaymentProfiles();
        $this->assertTrue(is_a($payments, 'Illuminate\Support\Collection'));
    }

    public function testCustomUserCanRetrieveFullPaymentProfiles()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
        $user->payment_profiles()->save($paymentProfile);
        $payments = $user->full_payment_profiles();
        dd($payments);
        //$this->assertTrue(is_a($payments, 'Illuminate\Support\Collection'));
    }

    public function testCustomUserCanChargePaymentProfile()
    {
        $user = User::create([
            'email' => 'aaronmichaelmusic@gmail.com',
            'fname' => 'Aaron',
            'lname' => 'Kaz',
            'company' => 'dieseljobs.com',
            'stripe_acct' => $this->stripe_acct
        ]);
        $paymentProfile = new PaymentProfile(['stripe_card_acct' => $this->stripe_card_acct]);
        $user->payment_profiles()->save($paymentProfile);

        $attrs = [
            'amount' => 10.50,
            'description' => 'Test charge',
            'statement_descriptor' => 'Report Fee',
            'metadata' => [
                'invoice_id' => "111"
            ]
        ];
        $charge = $paymentProfile->charge($attrs);
        $this->assertEquals(true, !empty($charge->id));
    }

}
