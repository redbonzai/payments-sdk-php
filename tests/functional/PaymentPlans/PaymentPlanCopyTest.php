<?php

use StackPay\Payments\Requests;
use StackPay\Payments\Structures;
use StackPay\Payments\PaymentPriority;

class PaymentPlanCopyTest extends FunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->merchant = new Structures\Merchant(123, 'hashkey_merchant123');
        $this->splitMerchant = new Structures\Merchant(124, 'hashkey_merchant124');
    }

    protected function buildPaymentPlanCopy()
    {
        $paymentPlan                  = new Structures\PaymentPlan();
        $paymentPlan->id              = 123;
        $paymentPlan->merchant        = $this->merchant;
        $paymentPlan->splitMerchant   = $this->splitMerchant;
        $paymentPlan->paymentPriority = PaymentPriority::EQUAL;

        return $paymentPlan;
    }

    public function testCreate()
    {
        // mock API success response
        $this->mockApiResponse(
            200, 
            [
                'data' => [
                    'id'                  => 12345,
                    'name'                => 'Monthly Plan - 6 Months',
                    'incoming_request_id' => 1111,
                    'down_payment_amount' => 0,
                    'down_payment_type'   => 'flat',
                    'merchant_id'         => 123,
                    'split_merchant_id'   => 124,
                    'payment_priority'    => PaymentPriority::EQUAL,
                    'configuration'       => [
                        'months'          => 6,
                        'day'             => 1,
                    ],
                ],
            ],
            $this->merchant->hashKey
        );

        $request = (new Requests\v1\PaymentPlanRequest($this->buildPaymentPlanCopy()))
            ->copyPaymentPlan();

        $this->response = $request->send();

        $this->assertResourceResponse();
    }

    public function testCreateWithValidationResponse()
    {
        // mock API success response
        $this->mockApiResponse(422, $this->invalidInputResponse());

        $request = (new Requests\v1\PaymentPlanRequest($this->buildPaymentPlanCopy()))
            ->copyPaymentPlan();

        $this->response = $request->send();

        $this->assertEquals($this->response->status(), 422);
        $this->assertNotTrue($this->response->success());
        $this->assertNotNull($this->response->error());

        $this->assertEquals($this->response->error()->getCode(), 403);
    }
}
