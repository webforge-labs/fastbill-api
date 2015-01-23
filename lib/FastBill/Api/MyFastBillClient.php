<?php

namespace FastBill\Api;

use Guzzle\HTTP\Client as GuzzleClient;

class MyFastBillClient extends AbstractFastBillClient {

  public function __construct(GuzzleClient $guzzleClient, Array $options) {
    $guzzleClient->setBaseUrl("https://my.fastbill.com/");
    parent::__construct($guzzleClient, $options);
  }
}