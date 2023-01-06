<?php

namespace App\Controllers;
use Barion\client\BarionClient;
use Barion\client\BarionEnvironment;
use Barion\client\Currency;
use Barion\client\FundingSourceType;
use Barion\client\ItemModel;
use Barion\client\PaymentTransactionModel;
use Barion\client\PaymentType;
use Barion\client\PreparePaymentRequestModel;
use Barion\client\UILocale;

class BarionController extends BaseController
{
    public function BarionPayment() {
        $myPosKey = "6f9e21760e714e008bfc3c0c5d8d40b9";
        $apiVersion = 2;
        $environment = "test";

        $BC = new BarionClient($myPosKey, $apiVersion, $environment);

        $item = new ItemModel();
        $item->Name = "Műbroki";
        $item->Description = "Fel kell dugni";
        $item->Quantity = 1;
        $item->Unit = "darab";
        $item->UnitPrice = 10000;
        $item->ItemTotal = 10000;
        $item->SKU = "ITEM-01";

        $trans = new PaymentTransactionModel();
        $trans->POSTransactionId = "TRANS-01";
        $trans->Payee = "habina.laszlo20@gmail.com";
        $trans->Total = 10000;
        $trans->Currency = Currency::HUF;
        $trans->Comment = "Teszt";
        $trans->AddItem($item);

        $psr = new PreparePaymentRequestModel();
        $psr->GuestCheckout = true;
        $psr->PaymentType = PaymentType::Immediate;
        $psr->FundingSources = array(FundingSourceType::All);
        $psr->PaymentRequestId = "TESTPAY-01";
        $psr->PayerHint = "user@example.com";
        $psr->Locale = UILocale::EN;
        $psr->Currency = Currency::HUF;
        $psr->OrderNumber = "ORDER-0001";
        $psr->AddTransaction($trans);

        $myPayment = $BC->PreparePayment($psr);

        if ($myPayment->RequestSuccessful === true) {
            header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
        }
        else {
            print_r($myPayment);
        }
    }
}