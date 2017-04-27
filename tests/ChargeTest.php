<?php

use TheLHC\CustomerPayment\Tests\TestCase;
use TheLHC\CustomerPayment\Tests\User;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ChargeTest extends TestCase
{

    public $stripe_id = "cus_AKqPLr4lGP5Xkc";
    public $charge_id = "ch_1A38Q9JfQv1xXyoLUSr3y0F7";

    public function testItCanCatchCreateChargeErrors()
    {
        $params = [
            'customer' => $this->stripe_id,
            'amount' => 'kjsadf',
            'capture' => false,
            'description' => 'Test charge',
            'statement_descriptor' => 'Report Fee',
        ];
        $charge = PaymentProcessor::createCharge($params);
        $this->assertEquals("TheLHC\CustomerPayment\ErrorBag", get_class($charge));
    }

    public function testCanCreateCharge()
    {
        $params = [
            'customer' => $this->stripe_id,
            'amount' => 20.00,
            'capture' => false,
            'description' => 'Test charge',
            'statement_descriptor' => 'Report Fee',
        ];
        $charge = PaymentProcessor::createCharge($params);
        $this->assertEquals(true, !empty($charge->id));
    }


    public function testCanRetrieveCharge()
    {
        $charge = PaymentProcessor::findCharge($this->charge_id);
        $this->assertEquals(true, !empty($charge->id));
    }


    public function testCanUpdateCharge()
    {
        $params = [
            'description' => 'Test charge update',
        ];
        $charge = PaymentProcessor::updateCharge($this->charge_id, $params);
        $this->assertEquals($params['description'], $charge->description);
    }


    public function testItCanCaptureCharge()
    {
        $amount = 19.50;
        $charge = PaymentProcessor::captureCharge($this->charge_id, $amount);
        $this->assertEquals(true, $charge->captured);
    }

}

/*
array:35 [
  "id" => "ch_1A37riJfQv1xXyoLYmVeuSdY"
  "object" => "charge"
  "amount" => 1050
  "amount_refunded" => 0
  "application" => null
  "application_fee" => null
  "balance_transaction" => null
  "captured" => false
  "created" => 1490897328
  "currency" => "usd"
  "customer" => "cus_AKqPLr4lGP5Xkc"
  "description" => "Test charge"
  "destination" => null
  "dispute" => null
  "failure_code" => null
  "failure_message" => null
  "fraud_details" => []
  "invoice" => null
  "livemode" => false
  "metadata" => []
  "on_behalf_of" => null
  "order" => null
  "outcome" => array:5 [
    "network_status" => "approved_by_network"
    "reason" => null
    "risk_level" => "normal"
    "seller_message" => "Payment complete."
    "type" => "authorized"
  ]
  "paid" => true
  "receipt_email" => null
  "receipt_number" => null
  "refunded" => false
  "refunds" => array:5 [
    "object" => "list"
    "data" => []
    "has_more" => false
    "total_count" => 0
    "url" => "/v1/charges/ch_1A37hUJfQv1xXyoLMFPkoDtw/refunds"
  ]
  "review" => null
  "shipping" => null
  "source" => array:23 [
    "id" => "card_1A0GbVJfQv1xXyoLWLC0TOHn"
    "object" => "card"
    "address_city" => null
    "address_country" => null
    "address_line1" => null
    "address_line1_check" => null
    "address_line2" => null
    "address_state" => null
    "address_zip" => null
    "address_zip_check" => null
    "brand" => "Visa"
    "country" => "US"
    "customer" => "cus_AKqPLr4lGP5Xkc"
    "cvc_check" => null
    "dynamic_last4" => null
    "exp_month" => 10
    "exp_year" => 2020
    "fingerprint" => "fqGBFGcbu1bVmxZI"
    "funding" => "credit"
    "last4" => "4242"
    "metadata" => []
    "name" => null
    "tokenization_method" => null
  ]
  "source_transfer" => null
  "statement_descriptor" => "Report Fee"
  "status" => "succeeded"
  "transfer_group" => null
]



array:35 [
  "id" => "ch_1A38Q9JfQv1xXyoLUSr3y0F7"
  "object" => "charge"
  "amount" => 2000
  "amount_refunded" => 50
  "application" => null
  "application_fee" => null
  "balance_transaction" => "txn_1A38QIJfQv1xXyoL9cxucfYP"
  "captured" => true
  "created" => 1490900097
  "currency" => "usd"
  "customer" => "cus_AKqPLr4lGP5Xkc"
  "description" => "Test charge"
  "destination" => null
  "dispute" => null
  "failure_code" => null
  "failure_message" => null
  "fraud_details" => []
  "invoice" => null
  "livemode" => false
  "metadata" => []
  "on_behalf_of" => null
  "order" => null
  "outcome" => array:5 [
    "network_status" => "approved_by_network"
    "reason" => null
    "risk_level" => "normal"
    "seller_message" => "Payment complete."
    "type" => "authorized"
  ]
  "paid" => true
  "receipt_email" => null
  "receipt_number" => null
  "refunded" => false
  "refunds" => array:5 [
    "object" => "list"
    "data" => array:1 [
      0 => array:11 [
        "id" => "re_1A38QIJfQv1xXyoLgom5Mx28"
        "object" => "refund"
        "amount" => 50
        "balance_transaction" => "txn_1A38QJJfQv1xXyoL6i6lNmFA"
        "charge" => "ch_1A38Q9JfQv1xXyoLUSr3y0F7"
        "created" => 1490900106
        "currency" => "usd"
        "metadata" => []
        "reason" => null
        "receipt_number" => null
        "status" => "succeeded"
      ]
    ]
    "has_more" => false
    "total_count" => 1
    "url" => "/v1/charges/ch_1A38Q9JfQv1xXyoLUSr3y0F7/refunds"
  ]
  "review" => null
  "shipping" => null
  "source" => array:23 [
    "id" => "card_1A0GbVJfQv1xXyoLWLC0TOHn"
    "object" => "card"
    "address_city" => null
    "address_country" => null
    "address_line1" => null
    "address_line1_check" => null
    "address_line2" => null
    "address_state" => null
    "address_zip" => null
    "address_zip_check" => null
    "brand" => "Visa"
    "country" => "US"
    "customer" => "cus_AKqPLr4lGP5Xkc"
    "cvc_check" => null
    "dynamic_last4" => null
    "exp_month" => 10
    "exp_year" => 2020
    "fingerprint" => "fqGBFGcbu1bVmxZI"
    "funding" => "credit"
    "last4" => "4242"
    "metadata" => []
    "name" => null
    "tokenization_method" => null
  ]
  "source_transfer" => null
  "statement_descriptor" => "Report Fee"
  "status" => "succeeded"
  "transfer_group" => null
]
 */
