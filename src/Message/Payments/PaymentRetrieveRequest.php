<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentRetrieveRequest extends AbstractPaymentReferenceRequest
{
//    public $responseCodeClassMap = [
//        '200'=>'Omnipay\GlobalCollect\Message\Payments\PaymentRetrieveResponse'
//    ];

    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}', array('{paymentId}' => $this->getTransactionReference()));
    }

    public function getHttpMethod()
    {
        return 'GET';
    }


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return array();
    }
}
