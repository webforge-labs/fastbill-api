<?php

namespace FastBill\Api;

use FastBill\Model\Customer;
use FastBill\Model\Invoice;
use FastBill\Model\InvoiceItem;
use FastBill\Model\Project;
use FastBill\Model\Expense;
use Guzzle\HTTP\Message\Request as GuzzleRequest;
use Guzzle\HTTP\Message\Response as GuzzleResponse;
use Guzzle\HTTP\Client as GuzzleClient;
use InvalidArgumentException;
use RuntimeException;
use Assert\Assertion;
use Webforge\Common\JS\JSONConverter;

class Client extends AbstractClient {

  protected $apiKey, $email;

  public function __construct(GuzzleClient $guzzleClient, Array $options) {
//    $guzzleClient->setBaseUrl("https://my.fastbill.com/");
    $guzzleClient->setBaseUrl("https://automatic.fastbill.com/api/1.0/api.php");
    parent::__construct($guzzleClient);

    if (!array_key_exists('apiKey', $options) || empty($options['apiKey'])) {
      throw new InvalidArgumentException("the key: 'apiKey' has to be set on options");
    }

    if (!array_key_exists('email', $options) || empty($options['email'])) {
      throw new InvalidArgumentException("the key: 'email' has to be set on options");
    }

    $this->apiKey = $options['apiKey'];
    $this->email = $options['email'];
    $this->guzzle->setDefaultOption('headers', array('Content-Type'=>'application/json'));
  }

  /**
   * @return FastBill\Model\Invoice
   */
  public function createInvoice(Invoice $invoice) {
    $requestBody = array(
      'SERVICE' => 'invoice.create',
      'DATA' => $invoice->serializeJSONXML()
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

    $invoice->setInvoiceId($jsonResponse->RESPONSE->INVOICE_ID);

    return $invoice;
  }

  /**
   * Creates a customer (not matter if it exists)
   * 
   * the object as parameter is returned as result but the new id will be set (or overridden)
   * 
   * @return FastBill\Model\Customer
   */
  public function createCustomer(Customer $customer) {
    $requestBody = array(
      'SERVICE' => 'customer.create',
      'DATA' => $customer->serializeJSONXML()
    );

    $jsonResponse = $this->validateResponse(
      $this->dispatchRequest(
        $this->createRequest('POST', '/', $requestBody)
      ),
      function ($response, &$msg) {
        $msg = 'key STATUS is not equal to success';
        return isset($response->STATUS) && $response->STATUS === 'success';
      }
    );

    $customer->setCustomerId($jsonResponse->RESPONSE->CUSTOMER_ID);

    return $customer;
  }

  public function getCustomers(Array $filters = array()) {
    $requestBody = (object) array(
      'SERVICE' => 'customer.get'
    );

    $this->filtersToXml($filters, $requestBody);

    $jsonResponse = $this->validateResponse(
      $this->dispatchRequest(
        $this->createRequest('POST', '/', $requestBody)
      ),
      function ($response, &$msg) {
        $msg = 'key CUSTOMERS is not set';
        return isset($response->CUSTOMERS);
      }
    );

    $customers = array();
    foreach ($jsonResponse->RESPONSE->CUSTOMERS as $xmlCustomer) {
      $customers[] = Customer::fromObject($xmlCustomer);
    }

    return $customers;
  }

  protected function filtersToXml(Array $filters, \stdClass $requestBody) {
    foreach ($filters as $name => $value) {
      if (!empty($value)) {

        if (!isset($requestBody->FILTER))
          $requestBody->FILTER = new \stdClass;

        $requestBody->FILTER->{mb_strtoupper($name)} = $value;
      }
    }
  }

  public function getInvoices(Array $filters = array()) {
    $requestBody = (object) array(
      'SERVICE' => 'invoice.get'
    );

    $this->filtersToXml($filters, $requestBody);

    $jsonResponse = $this->validateResponse(
      $this->dispatchRequest(
        $this->createRequest('POST', '/', $requestBody)
      ),
      function ($response, &$msg) {
        $msg = 'key INVOICES is not set';
        return isset($response->INVOICES);
      }
    );

    $invoices = array();
    foreach ($jsonResponse->RESPONSE->INVOICES as $xmlInvoice) {
      $invoices[] = Invoice::fromObject($xmlInvoice);
    }

    return $invoices;
  }

  public function getProjects(Array $filters = array()) {
    $requestBody = (object) array(
      'SERVICE' => 'project.get'
    );

    $this->filtersToXml($filters, $requestBody);

    $jsonResponse = $this->validateResponse(
      $this->dispatchRequest(
        $this->createRequest('POST', '/', $requestBody)
      ),
      function ($response, &$msg) {
        $msg = 'key PROJECTS is not set';
        return isset($response->PROJECTS);
      }
    );

    $projects = array();
    foreach ($jsonResponse->RESPONSE->PROJECTS as $xmlProject) {
      $projects[] = Project::fromObject($xmlProject);
    }

    return $projects;
  }

  public function getExpenses(Array $filters = array()) {
    $requestBody = (object) array(
      'SERVICE' => 'expense.get'
    );

    $this->filtersToXml($filters, $requestBody);

    $jsonResponse = $this->validateResponse(
      $this->dispatchRequest(
        $this->createRequest('POST', '/', $requestBody)
      ),
      function ($response, &$msg) {
        $msg = 'key EXPENSES is not set';
        return isset($response->EXPENSES);
      }
    );

    $expenses = array();
    foreach ($jsonResponse->RESPONSE->EXPENSES as $xml) {
      $expenses[] = Expense::fromObject($xml);
    }

    return $expenses;
  }

  protected function expandUrl($relativeResource) {
    return '/api/1.0/api.php';
  }

  protected function initRequest(GuzzleRequest $request) {
    $request->setAuth($this->email, $this->apiKey);
  }

  /**
   * @returns the whole response including REQUEST and RESPONSE ekys
   */
  protected function validateResponse(\stdClass $jsonResponse, \Closure $validateResponse) {
    $stringified = JSONConverter::create()->stringify($jsonResponse, JSONConverter::PRETTY_PRINT);

    if (!isset($jsonResponse->RESPONSE)) {
      throw new RuntimeException('The property response is expected in jsonResponse. Got: '.$stringified);
    }

    $msg = NULL;
    if (!$validateResponse($jsonResponse->RESPONSE, $msg)) {
      throw BadRequestException::fromResponse($jsonResponse);
    }

    return $jsonResponse;
  }
}
