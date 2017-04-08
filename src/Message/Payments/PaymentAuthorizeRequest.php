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
        $request = [
            'order'=>$this->createOrder()
        ];

        $request['fraudFields'] = $this->createFraudFields();

        if ($this->getToken()){
            $request['cardPaymentMethodSpecificInput'] = [
                'card'=> new \stdClass(),
                'isRecurring' => true,
                'recurringPaymentSequenceIndicator' => "recurring",
                'token' => $this->getToken(),
            ];
        }else{
            $request['cardPaymentMethodSpecificInput'] = [
                'card'=> new \stdClass()
            ];
        }

        return $request;
    }


    protected function createCustomer()
    {
        $card = $this->getCard();
        $order = $this->getOrder();

        $customer = [
            'billingAddress'=>[
                'city' => $card->getBillingCity(),
                'countryCode' => $card->getBillingCountry(),
                'state' => $card->getBillingState(),
                'street' => $card->getBillingAddress1(),
            ],
            'contactDetails'=>[
                'phoneNumber'=>$card->getPhone(),
                'emailAddress'=>$card->getEmail(),
                'emailMessageType'=>'html',
            ],
            'personalInformation'=>[
                'name'=>[
                    'firstName'=>$card->getFirstName(),
                    'surname'=>$card->getLastName(),
                ]
            ],
            "locale"=> $this->getLocale(),
            "merchantCustomerId"=> $order['customerId']
        ];

        return $customer;
    }

    protected function createOrder()
    {
        $order = [
            'amountOfMoney'=>$this->getAmountOfMoney(),
            'customer' => $this->createCustomer(),
            'references' => $this->createReferences(),
        ];

        return $order;

    }



    protected function createReferences()
    {
        $value = [];

        $order = $this->getOrder();

        $value['descriptor'] = $order['descriptor'];
        $value['merchantOrderId'] = $order['orderId'];
        $value['merchantReference'] = $order['merchantReference'];

        return $value;
    }

    protected function createFraudFields()
    {
        $value = [
            'customerIpAddress'=>$this->getClientIp(),
            'userData'=>[
                    0 =>'',
                    1 =>'',
                    2 =>'',
                    3 =>'',
                    4 =>'',
                    5 =>'',
                    6 =>'',
                    7 =>'',
                    8 =>'',
                    9 => 0,
                    10 =>'',
                    11 =>'',
                    12 =>'',
                    13 =>'',
                    14 => 'WO',
                    15 => ''
            ]
        ];

        return $value;
    }
}