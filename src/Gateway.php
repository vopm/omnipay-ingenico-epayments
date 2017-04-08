<?php

namespace Omnipay\GlobalCollect;

use Omnipay\Common\AbstractGateway;

/**
 * GlobalCollect Gateway.
 *
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\GlobalCollect\Message\AbstractRequest
 * @link https://developer.globalcollect.com/documentation/api/server/
 */
class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'GlobalCollect';
    }

    /**
     * Get the gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'testMode'      => false,
            'apiMerchantId' => '',
            'apiKeyId'      => '',
            'apiSecret'     => '',
            'apiVersion'    => 'v1',
            'integrator'    => null,
        ];
    }



    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentCaptureRequest', $parameters);
    }

    public function captureCancel(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentCaptureCancelRequest', $parameters);
    }

    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentRetrieveRequest', $parameters);
    }

    public function tokenize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentTokenizeRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentVoidRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentRefundRequest', $parameters);
    }

    public function approvePending(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\GlobalCollect\Message\Payments\PaymentPendingApproveRequest', $parameters);
    }


    /** parameters */
    public function getApiMerchantId()
    {
        return $this->getParameter('apiMerchantId');
    }

    public function setApiMerchantId($value)
    {
        return $this->setParameter('apiMerchantId', $value);
    }

    /**
     * Get the gateway API Key.
     *
     * @return string
     */
    public function getApiKeyId()
    {
        return $this->getParameter('apiKeyId');
    }

    /**
     * Set the gateway API Key.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiKeyId($value)
    {
        return $this->setParameter('apiKeyId', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getIntegrator()
    {
        return $this->getParameter('integrator');
    }

    public function setIntegrator($value)
    {
        return $this->setParameter('integrator', $value);
    }

    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }
}
