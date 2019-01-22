<?php

namespace StackPay\Payments\Requests\v1;

use StackPay\Payments\StackPay;
use StackPay\Payments\Structures;

class PaymentPlanRequest extends Request
{
    public $paymentPlan;
    public $subscription;

    public function __construct(
        Structures\PaymentPlan $paymentPlan = null,
        Structures\Subscription $subscription = null
    ) {
        parent::__construct();

        $this->paymentPlan = $paymentPlan;
        $this->subscription = $subscription;
    }

    public function copyPaymentPlan()
    {
        $this->method   = 'POST';
        $this->endpoint = '/api/merchants/' . $this->paymentPlan->merchant->id . '/payment-plans';
        $this->hashKey  = $this->paymentPlan->merchant->hashKey;
        $this->body     = $this->restTranslator->buildPaymentPlanCopyElement($this->paymentPlan);

        return $this;
    }

    public function getMerchantPaymentPlans()
    {
        $this->method   = 'GET';
        $this->endpoint = '/api/merchants/' . $this->paymentPlan->merchant->id . '/payment-plans';
        $this->hashKey  = $this->paymentPlan->merchant->hashKey;

        return $this;
    }

    public function getDefaultPaymentPlans()
    {
        $this->method   = 'GET';
        $this->endpoint = '/api/payment-plans';
        $this->hashKey  = StackPay::$privateKey;

        return $this;
    }

    public function createPaymentPlanSubscription()
    {
        $this->method   = 'POST';
        $this->endpoint = '/api/merchants/' . $this->paymentPlan->merchant->id . '/payment-plans/' . $this->paymentPlan->id . '/subscriptions';
        $this->hashKey  = $this->paymentPlan->merchant->hashKey;
        $this->body     = $this->restTranslator->buildPaymentPlanCreateSubscriptionElement($this->subscription);

        return $this;
    }
}
