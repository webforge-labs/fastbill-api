<?php

namespace FastBill\Api;

use Guzzle\HTTP\Client as GuzzleClient;

class FastBillAutomaticClient extends AbstractFastBillClient {

  public function __construct(GuzzleClient $guzzleClient, Array $options) {
    $guzzleClient->setBaseUrl("https://automatic.fastbill.com/");
    parent::__construct($guzzleClient, $options);
  }
}