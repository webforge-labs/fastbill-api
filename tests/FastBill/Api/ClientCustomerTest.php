<?php

namespace FastBill\Api;

use FastBill\Model\Customer;
use Webforge\Common\System\File;
use Guzzle\Http\Message\Request as GuzzleRequest;
use Webforge\Common\String as S;
use Webforge\Common\Preg;

class ClientCustomerTest extends \FastBill\Model\Test\ModelTestCase
{

    public function setUp()
    {
        $this->chainClass = __NAMESPACE__ . '\\Client';
        parent::setUp();

        $this->client = new \FastBill\Api\MyFastBillClient($this->getGuzzleMocker()->getClient(), $this->fastBillParameters);
    }

    public function testCustomersAreReturnedUnfiltered()
    {
        $this->getGuzzleMocker()->addResponse('FastBill/get-all-customers');
        $this->assertInternalType('array', $customers = $this->client->getCustomers());
        //$this->getGuzzleMocker()->recordLastResponse('FastBill/get-all-customers');

        $this->assertCount(2, $customers, 'should return the sample customers');
        $this->assertContainsOnlyInstancesOf('FastBill\Model\Customer', $customers);

        $this->assertEquals(
            $customers[0],
            $this->getCustomer('mueller')
        );

        $this->assertEquals(
            $customers[1],
            $this->getCustomer('lightspeed')
        );
    }

    public function testCustomersCanBeFilteredByTerm()
    {
        $this->getGuzzleMocker()->addResponse('FastBill/get-filtered-customers');
        $this->assertInternalType('array', $customers = $this->client->getCustomers(array('term' => 'müller')));
        //$this->getGuzzleMocker()->recordLastRequest('FastBill/get-filtered-customers');

        $this->assertCount(1, $customers, 'should return one customer found by term');
        $this->assertContainsOnlyInstancesOf('FastBill\Model\Customer', $customers);

        $this->assertEquals(
            $customers[0],
            $this->getCustomer('mueller')
        );
    }

    public function testCustomersCanBeReturnedEmpty()
    {
        $this->getGuzzleMocker()->addResponse('FastBill/get-empty-customers');
        $this->assertInternalType('array', $customers = $this->client->getCustomers());
        $this->assertCount(0, $customers);
    }

    public function testACustomerCanBeCreatedWithJustTheRequiredFields_forBusiness()
    {
        $this->getGuzzleMocker()->addResponse('FastBill/created-customer');
        $this->assertIsCustomer($this->client->createCustomer(
            Customer::fromArray(array(
                'customerType' => 'business',
                'organization' => '[A] [C]ompany that [M]anufactures [E]verything Inc.',
                'countryCode' => 'US',
                'paymentType' => Customer::PAYMENT_CREDITCARD
            ))
        ));
        //$this->getGuzzleMocker()->recordLastResponse('FastBill/created-customer');

        $this->assertCount(1, $requests = $this->getGuzzleMocker()->getReceivedRequests());
        $createRequest = $requests[0];

        $this->assertGuzzleRequestEquals(
            $this->getTestDirectory('requests/FastBill/')->getFile('create-customer.guzzle-request'),
            $createRequest
        );
    }
}
