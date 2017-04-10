<?php

namespace Omnipay\GlobalCollect\Message\Payments;


use Omnipay\GlobalCollect\Message\AbstractRequest;

abstract class AbstractPaymentReferenceRequest extends AbstractRequest
{

    public function getHttpMethod()
    {
        return 'POST';
    }
}
