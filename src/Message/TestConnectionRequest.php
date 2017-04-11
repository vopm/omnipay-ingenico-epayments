<?php

namespace Omnipay\GlobalCollect\Message;


class TestConnectionRequest extends AbstractRequest
{
    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndPoint()
    {
        return $this->buildUrl('/services/testconnection');
    }
}
