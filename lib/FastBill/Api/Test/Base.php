<?php

namespace FastBill\Api\Test;

use Webforge\Code\Test\GuzzleMocker;
use Guzzle\HTTP\Client as GuzzleClient;
use Guzzle\HTTP\Message\Request as GuzzleRequest;
use Guzzle\HTTP\Message\Response as GuzzleResponse;
use Guzzle\Tests\Http\Message\HeaderComparison;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Webforge\Common\JS\JSONConverter;
use Webforge\Common\System\File;
use Webforge\Common\ArrayUtil as A;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class Base extends \Webforge\Code\Test\Base {

  protected $guzzleMocker;

  protected $config;

  /**
   * if true then one facility has returned the debug output for this test
   */
  protected $debugged = FALSE;
  protected $isAcceptanceTest = FALSE;

  /**
   * @var GuzzleClient
   */
  protected $webClient;

  public function setUp() {  
    parent::setUp();

    $this->jsonc = new JSONConverter();

    $this->config = $this->jsonc->parseFile($this->getPackageDir('etc')->getFile('config.json'));
    $this->fastBillParameters = array('apiKey'=>$this->config->fastBill->apiKey, 'email'=>$this->config->fastBill->email);
  }

  protected function initWebClient() {
    $this->webClient = $this->createWebClient();
  }

  protected function getGuzzleMocker() {
    if (!isset($this->guzzleMocker)) {
      $this->guzzleMocker = $this->createGuzzleMocker();
    }

    return $this->guzzleMocker;
  }

  protected function createGuzzleMocker() {
    return new GuzzleMocker($this->getTestDirectory('responses/'));
  }

  protected function expectGuzzleResponse($name) {
    $this->guzzleMocker->addResponse($name);
  }

  protected function createMockedClient() {
    $guzzleClient = $this->getGuzzleMocker()->getClient();
    $guzzleClient->setBaseUrl('http://this-should-not-be-used');

    return $guzzleClient;
  }

  /**
   * @return GuzzleClient
   */
  protected function createWebClient() {
    return new GuzzleClient($this->config->web->url);
  }

  protected function dispatch(GuzzleRequest $request) {
    $this->request = $request;

    try {
      return $this->response = $request->send();

    } catch (\Guzzle\Http\Exception\BadResponseException $e) {
      $this->response = $e->getResponse();
      $this->debugged = TRUE;

      $this->fail($this->getRequestResponseDebug());
    }
  }

  protected function dispatchJSON(GuzzleRequest $request, $jsonType = 'object') {
    $response = $this->dispatch($request);

    return $this->assertJSONResponse($response, $jsonType);
  }

  protected function dispatchHTML(GuzzleRequest $request) {
    $response = $this->dispatch($request);

    return (string) $response->getBody();
  }

  /**
   * @param string $type object|array
   * @return decodedJson
   */
  protected function assertJSONResponse($response, $type = 'object') {
    if ($response instanceof GuzzleResponse) {
      $body = (string) $response->getBody();
    } elseif ($response instanceof SymfonyResponse) {
      $body = (string) $response->getContent();
    } else {
      $this->fail('unknown response type: '.gettype($response));
    }

    try {
      $this->assertInternalType($type, $json = $this->jsonc->parse($body), 'Response is not from correct JSON response type');
    } catch (\Webforge\Common\JS\JSONParsingException $e) {
      $this->response = $response;
      $this->fail($e->getMessage());
    }

    return $json;
  }

  protected function onNotSuccessfulTest(\Exception $e) {
    if ($this->isAcceptanceTest && !$this->debugged) {
      print $this->getRequestResponseDebug();
    }

    throw $e;
  }

  protected function getRequestResponseDebug() {
    $msg = '------------ Failing Test ------------'."\n";
    
    if (isset($this->request)) {      
      $msg .= '--------- Request ---------'."\n";
      $msg .= $this->request."\n";
    }

    if (isset($this->response)) {      
      $msg .= '--------- Response ---------'."\n";
      $msg .= $this->response;
      $msg .= "\n";
    }

    if (!isset($this->response) && !isset($this->request)) {
      $msg .= " (no debug info)"."\n";
    }

    $msg .= '------------ / Failing Test ------------'."\n";

    return $msg;
  }

  protected function injectMockedSession() {
    $this->container->injectSession($session = new Session(new MockArraySessionStorage())); 
    return $session;
  }

  protected function injectLogin() {
    $this->container->getSession()->set('mite.user', $this->config->mite->user);
    $this->container->getSession()->set('mite.apiKey', $this->config->mite->apiKey);
    $this->container->getSession()->set('fastBill.apiKey', $this->config->fastBill->apiKey);
    $this->container->getSession()->set('fastBill.email', $this->config->fastBill->email);
  }

  protected function injectFastBillClient() {
    $this->container->injectFastBillClient(
      new \FastBill\Api\Client(
        $client = $this->createMockedClient(),
        array('apiKey'=>$this->config->fastBill->apiKey, 'email'=>$this->config->fastBill->email)
      )
    );
  }

  // @TODO refactor to testplate?
  protected function assertGuzzleRequestEquals(File $file, GuzzleRequest $request) {
    $expectedRequest = \Guzzle\Http\Message\RequestFactory::getInstance()->fromMessage($file->getContents());
    $this->assertInstanceOf('Guzzle\Http\Message\Request', $expectedRequest, 'Could not parse the request from file: '.$file.' ');

    $expectedHeaders = $expectedRequest->getHeaders()->toArray();

    $actualHeaders = $request->getHeaders()->toArray();

    $filter = function($headerName, $value) {
      return !in_array(mb_strtolower($headerName), array('content-length', 'authorization', 'user-agent'));
    };

    $this->assertEquals(
      A::filterKeys($expectedHeaders, $filter),
      A::filterKeys($actualHeaders, $filter),
      'Request (Headers) do not match expected Request (Headers) in: '.$file
    );

    $expectedBody = (string) $expectedRequest->getBody();
    $actualBody = (string) $request->getBody();

    if (mb_strpos($expectedRequest->getHeader('Content-Type'),'application/json') !== FALSE) {
      $jsonc = JSONConverter::create();
      $expectedBody = $jsonc->parse($expectedBody);
      $actualBody = $jsonc->parse($actualBody);
    } else {
      $expectedBody = S::eolVisible($expectedBody);
      $actualBody = S::eolVisible($actualBody);
    }

    $this->assertEquals(
      $expectedBody,
      $actualBody,
      'Request (Body) does not match expected Request (Body) in: '.$file
    );
  }
}
