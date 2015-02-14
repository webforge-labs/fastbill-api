# FastBill API

(Partial) implementation of the API from fastbill.com and fastbill-automatic.com

## installation

Use [Composer](http://getcomposer.org) to install.
```
composer require webforge/fastbill-api:1.0.*@dev
```
(this will follow the development branch, there is no stable release yet)

## simple usage

```php
<?php
use FastBill\Api\MyFastBillClient;

$fastBill = MyFastBillClient::create(
  array(
    'apiKey'=>'b338250aebc2684673321c2ab05af4d68sAhXRFx7rie98DlHTFrppzH1cmlaY5y',
    'email'=>'billing@ps-webforge.com'
  )
);

$invoices = $fastBill->getInvoices(array('invoice_id'=>$invoiceId));
$invoice = array_pop($invoices);

$invoice->getInvoiceNumber();
$invoice->getTitle();
$zHd = $invoice->getFirstName().' '.$invoice->getLastName();

$customer = $fastBill->getCustomers(array('customer_id'=>$invoice->getCustomerId()));
```

### creating models
```php
$customer = Customer::fromArray(
  'customerId'=> 1234
));

```

## testing

to run the tests use:
```
phpunit
```

## roadmap

Status: This is a development version, yet! The API can be still a subject to changes until release 1.0.0. If you feel this should be already done right know, please open an issue.
Awdng startet the migration for using this library for the fastbill automatic api.

## Bugs

Please report Bugs / Enhancements / Suggestions or Questions with the issues feature on this repository.

## Contributors

 - [pscheit](https://github.com/pscheit)
 - [awdng](https://github.com/awdng)

## License

Copyright (c) 2015 ps-webforge.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
