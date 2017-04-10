<?php

namespace Omnipay\GlobalCollect\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * GlobalCollect ErrorResponse.
 *
 * This is the response class for all GlobalCollect Error responses.
 *
 * @see \Omnipay\Stripe\Gateway
 */
class ErrorResponse extends AbstractResponse
{

    /**
     * @return ErrorItem[]
     */
    public function getErrors()
    {
        $errors = array();
        foreach ($this->data['errors'] as $e)
        {
            $errors[] = ErrorItem::fromError($e);
        }

        return $errors;
    }

    public function isSuccessful()
    {
        return !isset($this->data['errorId']);
    }


    /**
     * @return mixed|null|ErrorItem
     */
    public function getError()
    {
        return ($errors = $this->getErrors()) ? array_shift($errors) : null;
    }
}
