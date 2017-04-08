<?php
/**
 * Created by PhpStorm.
 * User: one
 * Date: 4/8/17
 * Time: 12:20 AM
 */

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentVoidRequest extends AbstractPaymentReferenceRequest
{
    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/cancel', ['{paymentId}'=>$this->getTransactionReference()]);
    }
}