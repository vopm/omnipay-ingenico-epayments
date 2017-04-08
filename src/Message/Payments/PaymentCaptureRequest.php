<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentCaptureRequest extends AbstractPaymentReferenceRequest
{

    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/approve', ['{paymentId}'=>$this->getTransactionReference()]);
    }

    public function getData()
    {
        $request = new \stdClass();

        if ($this->getAmountInteger()){
            $request = [
                'amount'=>$this->getAmountInteger()
            ];
        }

        return $request;
    }
}