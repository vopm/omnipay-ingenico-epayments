<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentPurchaseRequest extends PaymentAuthorizeRequest
{

    public function getData()
    {
        $request = parent::getData();

        return $request;
    }
}
