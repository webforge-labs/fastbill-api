<?php

namespace FastBill\Api;

use FastBill\Model\Subscription;
use Webforge\Common\System\File;
use Guzzle\Http\Message\Request as GuzzleRequest;
use Webforge\Common\String as S;
use Webforge\Common\Preg;

class ClientSubscriptionTest extends \FastBill\Model\Test\ModelTestCase {
  
  public function setUp() {
    $this->chainClass = __NAMESPACE__ . '\\Client';
    parent::setUp();

    $this->client = new FastBillAutomaticClient($this->getGuzzleMocker()->getClient(), $this->fastBillParameters);
  }

  public function testSubscriptionsAreReturnedUnfiltered() {
    $this->getGuzzleMocker()->addResponse('FastBill/get-all-subscriptions');
    $this->assertInternalType('array', $subscriptions = $this->client->getSubscriptions());
//    $this->getGuzzleMocker()->recordLastResponse('FastBill/get-all-subscriptions');

    $this->assertCount(2, $subscriptions, 'should return the sample customers');
    $this->assertContainsOnlyInstancesOf('FastBill\Model\Subscription', $subscriptions);

    return;
  }
}
