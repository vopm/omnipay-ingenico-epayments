<?php

/**
 * GlobalCollect Abstract Request.
 */

namespace Omnipay\GlobalCollect\Message;

use DateTime;
use Guzzle\Common\Event;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GlobalCollect\Message\Payments\PaymentRetrieveResponse;
use stdClass;

/**
 * GlobalCollect Abstract Request.
 *
 * This is the parent class for all GlobalCollect requests.
 *
 * @method \Omnipay\GlobalCollect\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const SDK_VERSION = '5.4.0';

    const AUTHORIZATION_ID = 'GCS';

    const DATE_RFC2616 = 'D, d M Y H:i:s T';

    const AUTHORIZATION_TYPE = 'v1HMAC';

    const HASH_ALGORITHM = 'sha256';

    const MIME_APPLICATION_JSON = 'application/json';

    public $responseCodeClassMap = [
    ];

    public $defaultResponseClass = 'Omnipay\GlobalCollect\Message\Response';
    public $defaultErrorResponseClass = 'Omnipay\GlobalCollect\Message\ErrorResponse';

    abstract public function getHttpMethod();

    abstract public function getEndPoint();

    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $productionEndpoint = 'https://api.globalcollect.com';
    protected $preProductionEndpoint = 'https://api-preprod.globalcollect.com';
    protected $clientMetaInfo;

    public function buildUrl($path, $context = [])
    {
        return strtr('/{apiVersion}/{merchantId}', ['{apiVersion}' => $this->getApiVersion(), '{merchantId}' => $this->getApiMerchantId()]) . strtr($path, $context);
    }

    public function getApiMerchantId()
    {
        return $this->getParameter('apiMerchantId');
    }

    public function setApiMerchantId($value)
    {
        return $this->setParameter('apiMerchantId', $value);
    }

    public function getApiKeyId()
    {
        return $this->getParameter('apiKeyId');
    }

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

    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }

    public function getApiIntegrator()
    {
        return $this->getParameter('apiIntegrator');
    }

    public function setApiIntegrator($value)
    {
        return $this->setParameter('apiIntegrator', $value);
    }

    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    public function getOrder()
    {
        return $this->getParameter('order');
    }

    public function setOrder($order)
    {
        return $this->setParameter('order', $order);
    }

    public function getData()
    {
        return new \stdClass();
    }

    protected function getAmountOfMoney(){
        return [
            'amount' => (int)$this->getAmount()*100 ,
            'currencyCode' => $this->getCurrency(),
        ];
    }

    /**
     * @return mixed
     */
    public function getClientMetaInfo()
    {
        return $this->clientMetaInfo;
    }

    /**
     * @param mixed $clientMetaInfo
     */
    public function setClientMetaInfo($clientMetaInfo)
    {
        $this->clientMetaInfo = $clientMetaInfo;
    }

    public function getIdempotenceKey()
    {
        return $this->getParameter('idempotenceKey');
    }

    public function setIdempotenceKey($value)
    {
        return $this->setParameter('idempotenceKey', $value);
    }

    public function getBaseUrl()
    {
        return $this->getTestMode() ? $this->preProductionEndpoint : $this->productionEndpoint;
    }

    public function getCardTypes()
    {
        return [
            CreditCard::BRAND_VISA        => 1,
            CreditCard::BRAND_AMEX        => 2,
            CreditCard::BRAND_MASTERCARD  => 3,
            CreditCard::BRAND_MAESTRO     => 117,
            CreditCard::BRAND_JCB         => 125,
            CreditCard::BRAND_DISCOVER    => 128,
            CreditCard::BRAND_DINERS_CLUB => 132,
        ];
    }

    public function getCardTypesReverse()
    {
        return [
            1   => CreditCard::BRAND_VISA, // credit
            114 => CreditCard::BRAND_VISA, //debit
            2   => CreditCard::BRAND_AMEX,
            3   => CreditCard::BRAND_MASTERCARD, //credit
            119 => CreditCard::BRAND_MASTERCARD, //debit
            117 => CreditCard::BRAND_MAESTRO,
            125 => CreditCard::BRAND_JCB,
            128 => CreditCard::BRAND_DISCOVER,
            132 => CreditCard::BRAND_DINERS_CLUB,
        ];
    }


    public function sendData($data)
    {
        $httpRequest = $this->createRequest(
            $this->getHttpMethod(),
            $this->getBaseUrl() . $this->getEndPoint(),
            $data
        );

        return $this->buildResponse($httpRequest->send());
    }

    protected function createRequest($method, $uri, $data = null, $headers = [])
    {
        $this->httpClient->getEventDispatcher()->addListener('request.error', function (Event $event) {
            /**
             * @var \Guzzle\Http\Message\Response $response
             */
            $response = $event['response'];

            if ($response->isError()) {
                $event->stopPropagation();
            }
        });

        $httpRequest = $this->httpClient->createRequest(
            $method,
            $uri,
            array_merge($this->generateRequestHeaders(), $headers),
            json_encode($data)
        );

        return $httpRequest;
    }


    /**
     * @param \Guzzle\Http\Message\Response $httpResponse
     *
     * @return PaymentRetrieveResponse
     * @throws InvalidResponseException
     */
    protected function buildResponse($httpResponse)
    {
        $response = null;

        foreach ($this->responseCodeClassMap as $code => $className) {
            if (strcmp($httpResponse->getStatusCode(), $code) == 0) {
                $response = new $className($this, $httpResponse->json());
            }
        }

        if (!$response && $this->defaultResponseClass) {
            $response = new $this->defaultResponseClass($this, $httpResponse->json());
        }

        if (!$response && $this->defaultErrorResponseClass) {
            $response = new $this->defaultErrorResponseClass($this, $httpResponse->json());
        }

        if (!$response) {
            throw new InvalidResponseException();
        }

        return $response;
    }


    /**
     * @return string[]
     */
    public function generateRequestHeaders()
    {
        $contentType = static::MIME_APPLICATION_JSON;
        $rfc2616Date = $this->getRfc161Date();
        $requestHeaders = [];
        $requestHeaders['Content-Type'] = $contentType;
        $requestHeaders['Date'] = $rfc2616Date;
        if ($this->getClientMetaInfo()) {
            $requestHeaders['X-GCS-ClientMetaInfo'] = $this->getClientMetaInfo();
        }
        $requestHeaders['X-GCS-ServerMetaInfo'] = $this->getServerMetaInfoValue();
        if (strlen($this->getIdempotenceKey()) > 0) {
            $requestHeaders['X-GCS-Idempotence-Key'] = $this->getIdempotenceKey();
        }
        $requestHeaders['Authorization'] = $this->getAuthorizationHeaderValue($requestHeaders);

        return $requestHeaders;
    }

    /**
     * @return string
     */
    protected function getRfc161Date()
    {
        $dateTime = new DateTime('now');

        return $dateTime->format(static::DATE_RFC2616);
    }

    protected function getServerMetaInfoValue()
    {
        // use a stdClass instead of specific class to keep out null properties
        $serverMetaInfo = new stdClass();
        $serverMetaInfo->platformIdentifier = sprintf('%s; php version %s', php_uname(), phpversion());
        $serverMetaInfo->sdkIdentifier = 'PHPServerSDK/v' . static::SDK_VERSION;
        $serverMetaInfo->sdkCreator = 'Ingenico';

        $integrator = $this->getApiIntegrator();
        if (!is_null($integrator)) {
            $serverMetaInfo->integrator = $integrator;
        }

        // the sdkIdentifier contains a /. Without the JSON_UNESCAPED_SLASHES, this is turned to \/ in JSON.
        return base64_encode(json_encode($serverMetaInfo, JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param string[] $requestHeaders
     *
     * @return string
     */
    protected function getAuthorizationHeaderValue($requestHeaders)
    {
        return
            static::AUTHORIZATION_ID . ' ' . static::AUTHORIZATION_TYPE . ':' .
            $this->getApiKeyId() . ':' .
            base64_encode(
                hash_hmac(
                    static::HASH_ALGORITHM,
                    $this->getSignData($requestHeaders),
                    $this->getApiSecret(),
                    true
                )
            );
    }

    /**
     * @param string[] $requestHeaders
     *
     * @return string
     */
    protected function getSignData($requestHeaders)
    {
        $signData = $this->getHttpMethod() . "\n";
        if (isset($requestHeaders['Content-Type'])) {
            $signData .= $requestHeaders['Content-Type'] . "\n";
        } else {
            $signData .= "\n";
        }
        if (isset($requestHeaders['Date'])) {
            $signData .= $requestHeaders['Date'] . "\n";
        } else {
            $signData .= "\n";
        }
        $gcsHeaders = [];
        foreach ($requestHeaders as $headerKey => $headerValue) {
            if (preg_match('/X-GCS/i', $headerKey)) {
                $gcsHeaders[$headerKey] = $headerValue;
            }
        }
        ksort($gcsHeaders);
        foreach ($gcsHeaders as $gcsHeaderKey => $gcsHeaderValue) {
            $gcsEncodedHeaderValue = trim(preg_replace('/\r?\n[\h]*/', ' ', $gcsHeaderValue));

            $signData .= strtolower($gcsHeaderKey) . ':' . $gcsEncodedHeaderValue . "\n";
        }
        $signData .= $this->getEndPoint() . "\n";

        return $signData;
    }


}
