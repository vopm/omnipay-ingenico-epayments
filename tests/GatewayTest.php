<?php

namespace Omnipay\GlobalCollect;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setTestMode(true);
        $this->gateway->setApiIntegrator(getenv('GC_API_INTEGRATOR'));
        $this->gateway->setApiKeyId(getenv('GC_API_KEY'));
        $this->gateway->setApiMerchantId(getenv('GC_MERCHANT'));
        $this->gateway->setApiSecret(getenv('GC_API_SECRET'));
    }

    public function testRetrieve()
    {
        $this->setMockHttpResponse('payments/000000895910000023670000200001/retrieve.200.txt');

        $request = $this->gateway->fetchTransaction(['transactionReference' => '000000895910000023670000200001']);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentRetrieveRequest', $request);

        $response = $request->send();

        $this->assertEquals('000000895910000023670000200001', $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('payments/000000895910000023670000200001/authorize.200.txt');

        $request = $this->gateway->authorize([
            'amount'   => 828.00,
            'currency' => 'DOP',
            'token'    => 'e9296584-f278-4eef-920e-6d8cf17a6c60',
            'clientIp' => '127.0.0.1',
            'card'     => $this->getValidCard(),
            'transactionId'=>'f5f59512-aad3-1c44-298',
            'description'=>'Soft Descriptor',
            'order'=>[
                'customerId'=>888888,
                'orderId'=>111111,
            ]
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentAuthorizeRequest', $request);

        $this->assertSame('828.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testCapture(){
        $this->setMockHttpResponse('payments/000000895910000023670000200001/capture.200.txt');

        $request = $this->gateway->capture([
            'transactionReference'=>'000000895910000023670000200001'
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentCaptureRequest', $request);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testCaptureCancel(){
        $this->setMockHttpResponse('payments/000000895910000023670000200001/captureCancel.200.txt');

        $request = $this->gateway->captureCancel([
            'transactionReference'=>'000000895910000023670000200001'
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentCaptureCancelRequest', $request);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testTokenize(){
        $this->setMockHttpResponse('payments/000000895910000023670000200001/tokenize.200.txt');

        $request = $this->gateway->tokenize([
            'transactionReference'=>'000000895910000023670000200001'
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentTokenizeRequest', $request);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testVoid(){
        $this->setMockHttpResponse('payments/000000895910000023670000200001/cancel.200.txt');

        $request = $this->gateway->void([
            'transactionReference'=>'000000895910000023670000200001'
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentVoidRequest', $request);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testRefund(){
        $this->setMockHttpResponse('payments/000000895910000023670000200001/refund.200.txt');

        $request = $this->gateway->refund([
            'transactionReference'=>'000000895910000023670000200001',
            'amount'=>200.00,
            'card'     => $this->getValidCard()
        ]);

        $this->assertInstanceOf('Omnipay\GlobalCollect\Message\Payments\PaymentRefundRequest', $request);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function getValidCard()
    {
        return array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => rand(1, 12),
            'expiryYear' => gmdate('Y') + rand(1, 5),
            'cvv' => rand(100, 999),
            'billingAddress1' => '123 Billing St',
            'billingAddress2' => 'Billsville',
            'billingCity' => 'Billstown',
            'billingPostcode' => '12345',
            'billingState' => 'CA',
            'billingCountry' => 'US',
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
            'shippingCity' => 'Shipstown',
            'shippingPostcode' => '54321',
            'shippingState' => 'NY',
            'shippingCountry' => 'US',
            'shippingPhone' => '(555) 987-6543',
            'email' => 'test@me.com',
        );
    }

}
