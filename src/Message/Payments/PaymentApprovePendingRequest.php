<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentApprovePendingRequest extends AbstractPaymentReferenceRequest
{
    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/processchallenged', array('{paymentId}'=>$this->getTransactionReference()));
    }
}