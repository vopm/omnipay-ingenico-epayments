<?php

/**
 * GlobalCollect Gateway.
 */
namespace Omnipay\GlobalCollect;

use Omnipay\Common\AbstractGateway;

/**
 * GlobalCollect Gateway.
 *
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\GlobalCollect\Message\AbstractRequest
 * @link https://stripe.com/docs/api
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
        return array(
	        'testMode' => false,
	        'apiKeyId' => '',
            'apiSecret' => '',
            'integrator' => '',
        );
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

	public function getApiSecret(){
		return $this->getParameter('apiSecret');
	}

	public function setApiSecret($value){
		return $this->setParameter('apiSecret', $value);
	}

	public function getIntegrator(){
		return $this->getParameter('integrator');
	}

	public function setIntegrator($value){
		return $this->setParameter('integrator', $value);
	}


    /**
     * Authorize Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\GlobalCollect\Message\AuthorizeRequest
     */
//    public function authorize(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\GlobalCollect\Message\AuthorizeRequest', $parameters);
//    }


}
