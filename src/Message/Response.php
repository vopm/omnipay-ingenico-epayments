<?php

/**
 * GlobalCollect Response.
 */

namespace Omnipay\GlobalCollect\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * GlobalCollect Response.
 *
 * This is the base response class for all GlobalCollect requests.
 *
 * @see \Omnipay\GlobalCollect\Gateway
 */
class Response extends AbstractResponse
{
    const STATUS_ACCOUNT_VERIFIED = 'ACCOUNT_VERIFIED';
    const STATUS_CREATED = 'CREATED';
    const STATUS_REDIRECTED = 'REDIRECTED';
    const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    const STATUS_PENDING_FRAUD_APPROVAL = 'PENDING_FRAUD_APPROVAL';
    const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_AUTHORIZATION_REQUESTED = 'AUTHORIZATION_REQUESTED';
    const STATUS_CAPTURE_REQUESTED = 'CAPTURE_REQUESTED';
    const STATUS_CAPTURED = 'CAPTURED';
    const STATUS_PAID = 'PAID';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_REJECTED_CAPTURE = 'REJECTED_CAPTURE';
    const STATUS_REVERSED = 'REVERSED';
    const STATUS_CHARGEBACKED = 'CHARGEBACKED';
    const STATUS_REFUNDED = 'REFUNDED';


    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return !isset($this->data['errorId']);
    }

    public function isPending()
    {
        return isset($this->data['status']) ? ($this->data['status'] == self::STATUS_PENDING_FRAUD_APPROVAL) : null;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->data['status'] == self::STATUS_CANCELLED;
    }

    public function isCancellable()
    {
        return isset($this->data['statusOutput']) ? $this->data['statusOutput']['isCancellable'] : null;
    }

    public function isAuthorized()
    {
        return isset($this->data['statusOutput']) ? $this->data['statusOutput']['isCancellable'] : null;
    }

    public function isRefundable()
    {
        return isset($this->data['statusOutput']) ? $this->data['statusOutput']['isRefundable'] : null;
    }

    public function getTransactionReference()
    {
        if ($this->isSuccessful()) {
            if (isset($this->data['id'])) {
                return $this->data['id'];
            } elseif (isset($this->data['payment']) && isset($this->data['payment']['id'])) {
                return $this->data['payment']['id'];
            }
        }

        return null;
    }

    public function getTransactionId()
    {
        if ($this->isSuccessful()) {
            if (isset($this->data['paymentOutput']) && isset($this->data['paymentOutput']['references'])) {
                return $this->data['paymentOutput']['references']['merchantReference'];
            } elseif (isset($this->data['refundOutput']) && isset($this->data['refundOutput']['references'])) {
                return $this->data['refundOutput']['references']['merchantReference'];
            } elseif (
                isset($this->data['payment']['paymentOutput']) &&
                isset($this->data['payment']['paymentOutput']['references'])
            ) {
                return $this->data['payment']['paymentOutput']['references']['merchantReference'];
            }
            if (isset($this->data['order']) && isset($this->data['order']['references'])) {
                return $this->data['order']['references']['merchantReference'];
            }
        }

        return null;
    }

    public function getToken()
    {
        if (isset($this->data['token'])) {
            return $this->data['token'];
        }

        return null;
    }

    public function isNewToken()
    {
        if (isset($this->data['isNewToken'])) {
            return $this->data['isNewToken'];
        }

        return null;
    }

    /**
     * @return ErrorItem[]
     */
    public function getErrors()
    {
        $errors = array();
        foreach ($this->data['errors'] as $e) {
            $errors[] = ErrorItem::fromError($e);
        }

        return $errors;
    }

    /**
     * @return mixed|null|ErrorItem
     */
    public function getError()
    {
        return (!$this->isSuccessful() && ($errors = $this->getErrors())) ? array_shift($errors) : null;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful() && ($error = $this->getError())) {
            return $error->message;
        }

        return null;
    }

    public function getCode()
    {
        if (!$this->isSuccessful() && ($error = $this->getError())) {
            return $error->code;
        }

        return null;
    }
}
