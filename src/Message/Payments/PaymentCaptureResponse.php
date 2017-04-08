<?php

namespace Omnipay\GlobalCollect\Message\Payments;

use Omnipay\Common\Message\AbstractResponse;

class PaymentCaptureResponse extends AbstractResponse
{
    public function getTransactionReference()
    {
        return $this->data['paymentOutput']['references']['merchantReference'];
    }

    public function getTransactionId()
    {
        return $this->data['id'];
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return !isset($this->data['errorId']);
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->data['status'] == 'CANCELLED';
    }

    public function isCancellable()
    {
        return !isset($this->data['errorId']);
    }

    public function isAuthorized()
    {
        return !isset($this->data['errorId']);
    }

    public function isRefundable()
    {
        return !isset($this->data['errorId']);
    }
}
