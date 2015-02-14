<?php

namespace FastBill\Model\Test;

use FastBill\Model\Customer;
use FastBill\Model\Invoice;
use Webforge\Common\String as S;

abstract class ModelTestCase extends \FastBill\Api\Test\Base
{
    protected function assertGetterExists($customer, $getter)
    {
        if (!S::startsWith('get', $getter)) {
            $getter = 'get'.ucfirst($getter);
        }

        try {
            $value = $customer->$getter();
        } catch (\BadMethodCallException $e) {
            $this->fail($getter.'() should exist on object');
        } catch (\DomainException $e) {
            $this->fail($getter.'() cannot be called: '.$e->getMessage());
        }

        return $value;
    }

    public static function provideGetters()
    {
        $tests = array();

        foreach (static::$xmlNames as $xmlName => $getterName) {
            $tests[] = array($getterName);
        }

        return $tests;
    }

    protected function getXMLCustomers()
    {
        return array(
            'mueller' => (object) array(
                "CUSTOMER_ID" => "460166",
                "CUSTOMER_NUMBER" => "80",
                "DAYS_FOR_PAYMENT" => "14",
                "CREATED" => "2013-10-18 12:34:08",
                "PAYMENT_TYPE" => "1",
                "BANK_NAME" => "",
                "BANK_ACCOUNT_NUMBER" => "",
                "BANK_CODE" => "",
                "BANK_ACCOUNT_OWNER" => "",
                "SHOW_PAYMENT_NOTICE" => "1",
                "ACCOUNT_RECEIVABLE" => "",
                "CUSTOMER_TYPE" => "business",
                "TOP" => "0",
                "NEWSLETTER_OPTIN" => "0",
                "ORGANIZATION" => "Dr. Müller GmbH (DEMO)",
                "POSITION" => "",
                "SALUTATION" => "mr",
                "FIRST_NAME" => "Mark",
                "LAST_NAME" => "Müller",
                "ADDRESS" => "Bahnhofstr. 33",
                "ADDRESS_2" => "",
                "ZIPCODE" => "14480",
                "CITY" => "Potsdam",
                "COUNTRY_CODE" => "DE",
                "PHONE" => "0331-55512345",
                "PHONE_2" => "",
                "FAX" => "",
                "MOBILE" => "",
                "EMAIL" => "info@drmuellergmbh.de",
                "VAT_ID" => "",
                "CURRENCY_CODE" => "EUR",
                "LASTUPDATE" => "2013-10-18 12:34:08",
                "TAGS" => ""
            ),
            'lightspeed' => (object) array(
                "CUSTOMER_ID" => "460168",
                "CUSTOMER_NUMBER" => "81",
                "DAYS_FOR_PAYMENT" => "0",
                "CREATED" => "2013-10-18 12:34:08",
                "PAYMENT_TYPE" => "5",
                "BANK_NAME" => "",
                "BANK_ACCOUNT_NUMBER" => "",
                "BANK_CODE" => "",
                "BANK_ACCOUNT_OWNER" => "",
                "SHOW_PAYMENT_NOTICE" => "1",
                "ACCOUNT_RECEIVABLE" => "",
                "CUSTOMER_TYPE" => "business",
                "TOP" => "0",
                "NEWSLETTER_OPTIN" => "0",
                "ORGANIZATION" => "Lightspeed Logistik OHG (DEMO)",
                "POSITION" => "",
                "SALUTATION" => "mrs",
                "FIRST_NAME" => "Jessica",
                "LAST_NAME" => "Light",
                "ADDRESS" => "An der Autobahn 3",
                "ADDRESS_2" => "",
                "ZIPCODE" => "66123",
                "CITY" => "Saarbrücken",
                "COUNTRY_CODE" => "DE",
                "PHONE" => "0681-5559876",
                "PHONE_2" => "",
                "FAX" => "",
                "MOBILE" => "",
                "EMAIL" => "jl@lightspeed-online.de",
                "VAT_ID" => "",
                "CURRENCY_CODE" => "EUR",
                "LASTUPDATE" => "2013-10-18 12:34:08",
                "TAGS" => ""
            )
        );
    }

    protected function getCustomer($slug)
    {
        $customers = $this->getXmlCustomers();

        return \FastBill\Model\Customer::fromObject($customers[$slug]);
    }

    public function assertIsCustomer($customer)
    {
        $this->assertInstanceOf('FastBill\Model\Customer', $customer);

        return $customer;
    }

    public function assertIsInvoice($invoice)
    {
        $this->assertInstanceOf('FastBill\Model\Invoice', $invoice);

        return $invoice;
    }

    protected function getInvoicesXML()
    {
        return array(
            0 => (object) array(
                "CUSTOMER_ID" => 1,
                "INVOICE_DATE" => "2013-10-18",
                "ITEMS" => array(
                    (object) array(
                        "DESCRIPTION" => "Programmierung",
                        "UNIT_PRICE" => "50.00",
                        "VAT_PERCENT" => 19,
                        "QUANTITY" => 240
                    )
                )
            )
        );
    }

    public function getInvoice($slug)
    {
        $invoices = $this->getInvoicesXML();

        return \FastBill\Model\Invoice::fromObject($invoices[$slug]);
    }
}
