<?php

namespace FastBill\Api;

use Guzzle\HTTP\Client as GuzzleClient;

class MyFastBillClient extends AbstractFastBillClient
{

    public static function create(Array $options)
    {
        return new static(
            new GuzzleClient("https://my.fastbill.com/")
            $options
        );
    }
}
