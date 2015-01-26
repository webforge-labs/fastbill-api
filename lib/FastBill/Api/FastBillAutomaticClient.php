<?php

namespace FastBill\Api;

use FastBill\Model\Subscription;

use Guzzle\HTTP\Client as GuzzleClient;


class FastBillAutomaticClient extends AbstractFastBillClient {

  public function __construct(GuzzleClient $guzzleClient, Array $options) {
    $guzzleClient->setBaseUrl("https://automatic.fastbill.com/");
    parent::__construct($guzzleClient, $options);
  }

  /**
   * @return FastBill\Model\Subscription
   */
  public function createSubscription(Subscription $subscription) {
    $requestBody = array(
        'SERVICE' => 'subscription.create',
        'DATA' => $subscription->serializeJSONXML()
    );

    $jsonResponse = $this->validateResponse(
        $this->dispatchRequest(
            $this->createRequest('POST', '/', $requestBody)
        ),
        function ($response, &$msg) {
          $msg = 'STATUS is not equal to success';
          return isset($response->STATUS) && $response->STATUS === 'success';
        }
    );

    $subscription->setSubscriptionId($jsonResponse->RESPONSE->SUBSCRIPTION_ID);

    return $subscription;
  }

  public function getSubscriptions(Array $filters = array()) {
    $requestBody = (object) array(
        'SERVICE' => 'subscription.get'
    );

    $this->filtersToXml($filters, $requestBody);

    $jsonResponse = $this->validateResponse(
        $this->dispatchRequest(
            $this->createRequest('POST', '/', $requestBody)
        ),
        function ($response, &$msg) {
            $msg = 'key SUBSCRIPTIONS is not set';
            return isset($response->SUBSCRIPTIONS);
        }
    );

    $subscriptions = array();
    foreach ($jsonResponse->RESPONSE->SUBSCRIPTIONS as $xmlSubscription) {
        $subscriptions[] = Subscription::fromObject($xmlSubscription);
    }

    return $subscriptions;
  }
}