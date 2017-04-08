<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentCaptureCancelRequest extends AbstractPaymentReferenceRequest
{
    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/cancelapproval', ['{paymentId}'=>$this->getTransactionReference()]);
    }
}