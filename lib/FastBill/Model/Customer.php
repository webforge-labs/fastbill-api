<?php

namespace FastBill\Model;

class Customer extends AbstractModel
{
    const TYPE_BUSINESS = 'business';
    const TYPE_CONSUMER = 'consumer';

    const PAYMENT_BANKTRANSFER = 1; //Überweisung
    const PAYMENT_DIRECTDEBIT = 2;  //Lastschrift
    const PAYMENT_CASH = 3;         //Bar
    const PAYMENT_PAYPAL = 4;       //Paypal
    const PAYMENT_ADVANCEPAYMENT = 5; //Vorkasse
    const PAYMENT_CREDITCARD = 6;   //Kreditkarte
    const PAYMENT_CREDIT_CARD = 6;   //Kreditkarte

    protected static $xmlProperties = array(
        'CUSTOMER_ID' => 'customerId',
        'CUSTOMER_NUMBER' => 'customerNumber',
        'CUSTOMER_EXT_UID' => 'customerExtUid',
        'CREATED' => 'created',
        'CUSTOMER_TYPE' => 'customerType',
        'TOP' => 'top',
        'ORGANIZATION' => 'organization',
        'POSITION' => 'position',
        'SALUTATION' => 'salutation',
        'FIRST_NAME' => 'firstName',
        'LAST_NAME' => 'lastName',
        'ADDRESS' => 'address',
        'ADDRESS_2' => 'address2',
        'ZIPCODE' => 'zipcode',
        'CITY' => 'city',
        'COUNTRY_CODE' => 'countryCode',
        'PHONE' => 'phone',
        'PHONE_2' => 'phone2',
        'FAX' => 'fax',
        'MOBILE' => 'mobile',
        'EMAIL' => 'email',
        'HASH' => 'hash',

        // missing in docs, but in API
        'NEWSLETTER_OPTIN' => 'newsletterOptin',
        'LASTUPDATE' => 'lastUpdate',
        'TAGS' => 'tags',

        'ACCOUNT_RECEIVABLE' => 'accountReceivable',
        'CURRENCY_CODE' => 'currencyCode',
        'VAT_ID' => 'vatId',
        'DAYS_FOR_PAYMENT' => 'daysForPayment',
        'PAYMENT_TYPE' => 'paymentType',
        'SHOW_PAYMENT_NOTICE' => 'showPaymentNotice',
        'BANK_NAME' => 'bankName',
        'BANK_CODE' => 'bankCode',
        'BANK_ACCOUNT_NUMBER' => 'bankAccountNumber',
        'BANK_ACCOUNT_OWNER' => 'bankAccountOwner'
    );
}
