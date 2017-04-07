<?php

/**
 * GlobalCollect Abstract Request.
 */
namespace Omnipay\GlobalCollect\Message;

/**
 * GlobalCollect Abstract Request.
 *
 * This is the parent class for all GlobalCollect requests.
 *
 * @method \Omnipay\GlobalCollect\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $productionEndpoint = 'https://api.globalcollect.com';
    protected $preProductionEndpoint = 'https://api-preprod.globalcollect.com';

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

    abstract public function getEndpoint();

    abstract public function getHttpMethod();

    public function sendData($data)
    {
        // Stripe only accepts TLS >= v1.2, so make sure Curl is told
        $config = $this->httpClient->getConfig();
        $curlOptions = $config->get('curl.options');
        $curlOptions[CURLOPT_SSLVERSION] = 6;
        $config->set('curl.options', $curlOptions);
        $this->httpClient->setConfig($config);
        
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $data
        );
        $httpResponse = $httpRequest
            ->setHeader('Authorization', 'Basic '.base64_encode($this->getApiKey().':'))
            ->send();
        
        $this->response = new Response($this, $httpResponse->json());
        
        if ($httpResponse->hasHeader('Request-Id')) {
            $this->response->setRequestId((string) $httpResponse->getHeader('Request-Id'));
        }

        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParameter('source');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setSource($value)
    {
        return $this->setParameter('source', $value);
    }


    /**
     * Get the card data.
     *
     * Because the stripe gateway uses a common format for passing
     * card data to the API, this function can be called to get the
     * data from the associated card object in the format that the
     * API requires.
     *
     * @return array
     */
    protected function getCardData()
    {
        $card = $this->getCard();
        $card->validate();

        $data = array();
        $data['object'] = 'card';
        $data['number'] = $card->getNumber();
        $data['exp_month'] = $card->getExpiryMonth();
        $data['exp_year'] = $card->getExpiryYear();
        if ($card->getCvv()) {
            $data['cvc'] = $card->getCvv();
        }
        $data['name'] = $card->getName();
        $data['address_line1'] = $card->getAddress1();
        $data['address_line2'] = $card->getAddress2();
        $data['address_city'] = $card->getCity();
        $data['address_zip'] = $card->getPostcode();
        $data['address_state'] = $card->getState();
        $data['address_country'] = $card->getCountry();
        $data['email']           = $card->getEmail();

        return $data;
    }
}
