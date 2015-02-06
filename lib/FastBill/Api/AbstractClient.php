<?php

namespace FastBill\Api;

use Guzzle\HTTP\Client as GuzzleClient;
use Guzzle\HTTP\Message\Request as GuzzleRequest;

abstract class AbstractClient
{

    /**
     * @var Guzzle\HTTP\Client
     */
    protected $guzzle;

    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Sends the Guzzle Request to the server and returns the parsed result
     *
     * @return mixed the result parsed
     */
    public function dispatchRequest(GuzzleRequest $request)
    {
        $response = $request->send();

        return $this->parseJSON($json = (string)$response->getBody());
    }

    /**
     * @return Guzzle\HTTP\Message\Request
     */
    public function createRequest($method, $relativeResource, $body = NULL)
    {
        $request = $this->guzzle->createRequest($method, $this->expandurl($relativeResource));
        $this->initRequest($request);

        if ($body) { // assert object/array
            if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
                $jsonString = json_encode($body, JSON_PRETTY_PRINT);
            } else {
                $jsonString = json_encode($body);
            }

            $request->setBody($jsonString);
        }

        return $request;
    }

    protected function initRequest(GuzzleRequest $request)
    {
        //s.th. like: $request->setAuth($this->apiKey, '');
    }

    /**
     * Returns a realtive resource without some api constraints
     *
     * some apis have urls like: /api/v1/
     * this helps to expand those v1/ parameters
     * @return string
     */
    protected function expandUrl($relativeResource)
    {
        return $relativeResource;
    }

    /**
     * @return array|object
     */
    protected function parseJSON($jsonString)
    {
        $json = json_decode($jsonString);

        if ($json === NULL) {
            // workaround for JSON Syntax bug

            $patchedJSON = preg_replace("/,[\r\n]*}\s*$/", '}', $jsonString);
            $json = json_decode($patchedJSON);
        }

        if ($json === NULL) {
            throw new \RuntimeException('API does return invalid JSON: <<<JSON' . "\n" . $jsonString . "\nJSON");
        }

        return $json;
    }
}
