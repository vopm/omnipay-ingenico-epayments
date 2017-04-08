<?php

namespace Omnipay\GlobalCollect\Message\Payments;

use Omnipay\GlobalCollect\Message\Response;

class PaymentAuthorizeResponse extends Response
{
    public function getTransactionReference()
    {
        return $this->data['paymentOutput']['references']['merchantReference'];
    }
}
