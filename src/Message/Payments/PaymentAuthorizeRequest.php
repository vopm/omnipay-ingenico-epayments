<?php

namespace Omnipay\GlobalCollect\Message\Payments;

use Omnipay\GlobalCollect\Message\AbstractRequest;

class PaymentAuthorizeRequest extends AbstractRequest
{
    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndPoint()
    {
        return $this->buildUrl('/payments');
    }

    public function getData()
    {
        $request = array(
            'order' => $this->createOrder()
        );

        $request['fraudFields'] = $this->createFraudFields();

        if ($this->getToken()) {
            $request['cardPaymentMethodSpecificInput'] = array(
                'card'                              => new \stdClass(),
                'isRecurring'                       => true,
                'recurringPaymentSequenceIndicator' => "recurring",
                'token'                             => $this->getToken(),
            );
        } else {
            $request['cardPaymentMethodSpecificInput'] = array(
                'card' => new \stdClass()
            );
        }

        return $request;
    }


    protected function createCustomer()
    {
        $card = $this->getCard();
        $order = $this->getOrder();

        $customer = array(
            'billingAddress'      => array(
                'city'        => $card->getBillingCity(),
                'countryCode' => $card->getBillingCountry(),
                'state'       => $card->getBillingState(),
                'street'      => $card->getBillingAddress1(),
            ),
            'contactDetails'      => array(
                'phoneNumber'      => $card->getPhone(),
                'emailAddress'     => $card->getEmail(),
                'emailMessageType' => 'html',
            ),
            'personalInformation' => array(
                'name' => array(
                    'firstName' => $card->getFirstName(),
                    'surname'   => $card->getLastName(),
                )
            ),
            "locale"              => $this->getLocale(),
            "merchantCustomerId"  => $order['customerId']
        );

        return $customer;
    }

    protected function createOrder()
    {
        $order = array(
            'amountOfMoney' => $this->getAmountOfMoney(),
            'customer'      => $this->createCustomer(),
            'references'    => $this->createReferences(),
        );

        return $order;

    }


    protected function createReferences()
    {
        $value = array();

        $order = $this->getOrder();

        $value['descriptor'] = $this->getDescription();
        $value['merchantOrderId'] = $order['orderId'];
        $value['merchantReference'] = $this->getTransactionId();

        return $value;
    }

    protected function createFraudFields()
    {
        $value = array(
            'customerIpAddress' => $this->getClientIp(),
            'userData'          => array(
                0  => '',
                1  => '',
                2  => '',
                3  => '',
                4  => '',
                5  => '',
                6  => '',
                7  => '',
                8  => '',
                9  => '',
                10 => '',
                11 => '',
                12 => '',
                13 => '',
                14 => '',
                15 => ''
            )
        );

        return $value;
    }
}
