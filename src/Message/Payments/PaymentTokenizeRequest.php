<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentTokenizeRequest extends AbstractPaymentReferenceRequest
{
    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/tokenize', array('{paymentId}'=>$this->getTransactionReference()));
    }
}
