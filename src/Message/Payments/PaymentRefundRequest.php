<?php

namespace Omnipay\GlobalCollect\Message\Payments;


class PaymentRefundRequest extends AbstractPaymentReferenceRequest
{

    public function getEndPoint()
    {
        return $this->buildUrl('/payments/{paymentId}/refund', array('{paymentId}'=>$this->getTransactionReference()));
    }

    public function getData()
    {
        $request = array(
            'amountOfMoney'=>$this->getAmountOfMoney(),
            'customer'=>$this->createCustomer(),
        );



        return $request;
    }

    private function createCustomer()
    {
        $card = $this->getCard();

        $customer = array(
            'address'=>array(
                'name'=>array(
                    'firstName'=>$card->getFirstName(),
                    'surname'=>$card->getLastName(),
                ),
                'city' => $card->getBillingCity(),
                'countryCode' => $card->getBillingCountry(),
                'state' => $card->getBillingState(),
                'street' => $card->getBillingAddress1(),
            ),
            'contactDetails'=>array(
                'phoneNumber'=>$card->getPhone(),
                'emailAddress'=>$card->getEmail(),
                'emailMessageType'=>'html',
            )
        );

        return $customer;
    }
}