<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentCaptureRequest extends AbstractPaymentReferenceRequest
{

    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/approve', array('{paymentId}'=>$this->getTransactionReference()));
    }

    public function getData()
    {
        $request = new \stdClass();

        if ($this->getAmountInteger()){
            $request = array(
                'amount'=>$this->getAmountInteger()
            );
        }

        return $request;
    }
}