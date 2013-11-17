<?php

namespace FastBill\Model;

use Webforge\Common\ArrayUtil as A;
use Webforge\Common\String as S;

class CustomerTest extends \FastBill\Model\Test\ModelTestCase {

  static $xmlNames = array(
    'CUSTOMER_ID'=>'customerId',
    'CUSTOMER_NUMBER'=>'customerNumber',
    'CREATED'=>'created',
    'CUSTOMER_TYPE'=>'customerType',
    'TOP'=>'top',
    'ORGANIZATION'=>'organization',
    'POSITION'=>'position',
    'SALUTATION'=>'salutation',
    'FIRST_NAME'=>'firstName',
    'LAST_NAME'=>'lastName',
    'ADDRESS'=>'address',
    'ADDRESS_2'=>'address2',
    'ZIPCODE'=>'zipcode',
    'CITY'=>'city',
    'COUNTRY_CODE'=>'countryCode',
    'PHONE'=>'phone',
    'PHONE_2'=>'phone2',
    'FAX'=>'fax',
    'MOBILE'=>'mobile',
    'EMAIL'=>'email',
    'ACCOUNT_RECEIVABLE'=>'accountReceivable',
    'CURRENCY_CODE'=>'currencyCode',
    'VAT_ID'=>'vatId',
    'DAYS_FOR_PAYMENT'=>'daysForPayment',
    'PAYMENT_TYPE'=>'paymentType',
    'SHOW_PAYMENT_NOTICE'=>'showPaymentNotice',
    'BANK_NAME'=>'bankName',
    'BANK_CODE'=>'bankCode',
    'BANK_ACCOUNT_NUMBER'=>'bankAccountNumber',
    'BANK_ACCOUNT_OWNER'=>'bankAccountOwner'
  );
  
  /**
   * @dataProvider provideGetters
   */
  public function testCustomerHasSpecificGettersEven_IfValueIsNotProvided($getter) {
    $customer = Customer::fromArray(array());

    $this->assertIsCustomer($customer);
    $this->assertGetterExists($customer, $getter);
  }

  /**
   * @dataProvider provideGetters
   */
  public function testCustomerHasSpecificGettersfromObject_EvenIfValueIsNotProvided($getter) {
    $customer = Customer::fromObject(new \stdClass());

    $this->assertGetterExists($customer, $getter);
  }

  public function testACustomerIdCanBeModified() {
    $mueller = $this->getCustomer('mueller');

    $newValue = 'Müller GmbH';
    $this->assertNotEquals($newValue, $mueller->getOrganization());
    $mueller->setOrganization($newValue);
    $this->assertEquals($newValue, $mueller->getOrganization());
  }

  public function testCustomerCanHaveValuesPassedWithXMLObject() {
    $customer = $this->getCustomer('mueller');

    $this->assertEquals('Dr. Müller GmbH (DEMO)', $customer->getOrganization());
    $this->assertEquals('info@drmuellergmbh.de', $customer->getEmail());
    $notEmpties = array('customerId', 'customerNumber', 'organization', 'address', 'firstName', 'lastName');

    foreach (self::$xmlNames as $xmlName => $fieldName) {
      $value = $this->assertGetterExists($customer, $fieldName);

      if (in_array($fieldName, $notEmpties)) {
        $this->assertNotEmpty($value, 'value for '.$fieldName.'should be somehow defined');
      }
    }
  }

  public function testCustomerCanBeSerializedToXMLStyleJSONFields() {
    $json = $this->getCustomer('lightspeed')->
serializeJSONXML();

    $this->assertInternalType('object', $json);

    $expectedProperties = array(
      "CUSTOMER_ID"=>"460168",
      "CUSTOMER_NUMBER"=>"81",
      "DAYS_FOR_PAYMENT"=>"0",
      "CREATED"=>"2013-10-18 12:34:08",
      "PAYMENT_TYPE"=>"5",
      "BANK_NAME"=>"",
      "BANK_ACCOUNT_NUMBER"=>"",
      "BANK_CODE"=>"",
      "BANK_ACCOUNT_OWNER"=>"",
      "SHOW_PAYMENT_NOTICE"=>"1",
      "ACCOUNT_RECEIVABLE"=>"",
      "CUSTOMER_TYPE"=>"business",
      "TOP"=>"0",
      "NEWSLETTER_OPTIN"=>"0",
      "ORGANIZATION"=>"Lightspeed Logistik OHG (DEMO)",
      "POSITION"=>"",
      "SALUTATION"=>"mrs",
      "FIRST_NAME"=>"Jessica",
      "LAST_NAME"=>"Light",
      "ADDRESS"=>"An der Autobahn 3",
      "ADDRESS_2"=>"",
      "ZIPCODE"=>"66123",
      "CITY"=>"Saarbrücken",
      "COUNTRY_CODE"=>"DE",
      "PHONE"=>"0681-5559876",
      "PHONE_2"=>"",
      "FAX"=>"",
      "MOBILE"=>"",
      "EMAIL"=>"jl@lightspeed-online.de",
      "VAT_ID"=>"",
      "CURRENCY_CODE"=>"EUR",
      "LASTUPDATE"=>"2013-10-18 12:34:08",
      "TAGS"=>""
    );

    $attributesDebug = count($keys = array_keys((array) $json)) > 0 ? implode(", ", $keys) : '(none)';
    foreach ($expectedProperties as $prop => $value) {
      $this->assertObjectHasAttribute($prop, $json, 'serialized customer should have property: '.$prop."\nAvaible attributes:\n".$attributesDebug);
      $this->assertEquals($value, $json->$prop, 'value for '.$prop.' does not match');
    }
  }
}
