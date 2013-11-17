<?php

namespace FastBill\Model;

use Webforge\Common\ArrayUtil as A;
use Webforge\Common\String as S;

class InvoiceTest extends \FastBill\Model\Test\ModelTestCase {

  public function setUp() {
    parent::setUp();

    $this->invoice = Invoice::fromArray(array());
  }

  static $xmlNames = array(
    'INVOICE_ID'=>'invoiceId',
    'CUSTOMER_COSTCENTER_ID'=>'customerCostcenterId',
    'PROJECT_ID'=>'projectId',
    'INVOICE_DATE'=>'invoiceDate',
    'INTROTEXT'=>'introtext'

    // are this readonly values or values from the customer linked?
    //'FIRST_NAME'=>'firstName',
    //'LAST_NAME'=>'lastName',

    // usw: feel free to add some tests here
  );

  public function testfromArrayReturnsInvoice() {
    $this->assertIsInvoice(Invoice::fromArray(array()));
  }
  
  /**
   * @dataProvider provideGetters
   */
  public function testInvoiceHasSpecificGettersEven_IfValueIsNotProvided($getter) {
    $invoice = Invoice::fromArray(array());
    
    $this->assertGetterExists($invoice, $getter);
  }

  /**
   * @dataProvider provideGetters
   */
  public function testInvoiceHasSpecificGettersfromObject_EvenIfValueIsNotProvided($getter) {
    $invoice = Invoice::fromObject(new \stdClass());

    $this->assertGetterExists($invoice, $getter);
  }

  public function testDataCanBeInJsonNameFormat() {
    $xmlInvoice = (object) array(
    "CUSTOMER_ID"=>1,
    "INVOICE_DATE"=>"2013-10-18",
    "ITEMS"=>array(
      (object) array(
        "DESCRIPTION"=>"Programmierung",
        "UNIT_PRICE"=>"50.00",
        "VAT_PERCENT"=>19,
        "QUANTITY"=>240
        )
      )
    );

    $jsonInvoice = (object) array(
      'customerId'=>1,
      'invoiceDate'=>'2013-10-18',
      'items'=>array(
        (object) array(
          "description"=>"Programmierung",
          "unitPrice"=>"50.00",
          "vatPercent"=>19,
          "quantity"=>240
        )
      )
    );

    $this->assertEquals(
      $invoice = Invoice::fromArray((array) $jsonInvoice),
      Invoice::fromObject($xmlInvoice)
    );

    $this->assertInstanceOf(__NAMESPACE__.'\InvoiceItem', A::index($invoice->getItems(), 0));
  }

  public function testAInvoiceIdCanBeModified() {
    $someInvoice = $this->getInvoice(0);

    $newValue = 'Vielen Dank für Ihren Auftrag';
    $this->assertNotEquals($newValue, $someInvoice->getIntrotext());
    $someInvoice->setIntrotext($newValue);
    $this->assertEquals($newValue, $someInvoice->getIntrotext());
  }

  public function testInvoiceCanHaveValuesPassedWithXMLObject() {
    $invoice = $this->getInvoice(0);

    $this->assertEquals(1, $invoice->getCustomerId());
    $this->assertEquals('2013-10-18', $invoice->getInvoiceDate());

    $this->assertInternalType('array', $items = $invoice->getItems());
    $this->assertCount(1, $items);
    $this->assertContainsOnlyInstancesOf(__NAMESPACE__.'\InvoiceItem', $items);
    $this->assertEquals('Programmierung', $items[0]->getDescription());
  }

  public function testInvoiceCanBeSerializedToXMLStyleJSONFields() {
    $json = $this->getInvoice(0)->serializeJSONXML();

    $this->assertInternalType('object', $json);

    $expectedProperties = array(
      "CUSTOMER_ID"=>1,
      "INVOICE_DATE"=>"2013-10-18",
    );

    $attributesDebug = count($keys = array_keys((array) $json)) > 0 ? implode(", ", $keys) : '(none)';
    foreach ($expectedProperties as $prop => $value) {
      $this->assertObjectHasAttribute($prop, $json, 'serialized invoice should have property: '.$prop."\nAvaible attributes:\n".$attributesDebug);
      $this->assertEquals($value, $json->$prop, 'value for '.$prop.' does not match');
    }
  }

  public function testAbstractModelThrowsDomainExceptionOnUnknownProperties() {
    $this->setExpectedException('DomainException');
    $this->invoice->getSomethingNotDefined();
  }

  public function testAbstractModelThrowsDomainExceptionOnSettingUnknownProperties() {
    $this->setExpectedException('DomainException');
    $this->invoice->setSomethingNotDefined('value');
  }

  public function testAbstractModelThrowsBadMethodCallExceptionOnUnknownethods() {
    $this->setExpectedException('BadMethodCallException');
    $this->invoice->somethingNotDefined();
  }

  public function testAbstractModelHasAToString() {
    $this->assertContains('Invoice', (string) $this->invoice);
  }
}
