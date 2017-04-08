<?php

namespace Omnipay\GlobalCollect\Message;


class ErrorItem extends \stdClass
{
    public $id;
    public $code;
    public $category;
    public $message;
    public $httpStatusCode;

    public static function fromError($e)
    {
        $instance = new self();
        $instance->id = $e['id'];
        $instance->code = $e['code'];
        $instance->message = $e['message'];
        $instance->category = $e['category'];
        $instance->httpStatusCode = $e['httpStatusCode'];
    }
}