<?php

namespace FastBill\Model;

class Subscription extends AbstractModel
{
    protected static $xmlProperties = array(
        'SUBSCRIPTION_ID' => 'subscriptionId',
        'CUSTOMER_ID' => 'customerId',
        'SUBSCRIPTION_EXT_UID' => 'subscriptionExtUid',
        'ARTICLE_NUMBER' => 'articleNumber',

        'COUPON' => 'coupon',
        'TITLE' => 'title',
        'UNIT_PRICE' => 'unitPrice',
        'DESCRIPTION' => 'description',
        'CURRENCY_CODE' => 'currencyCode',
        'INVOICE_TITLE' => 'invoiceTitle',
        'START_DATE' => 'startDate',
        'CANCELLATION_DATE' => 'cancellationDate',
        'EXPIRATION_DATE' => 'expirationDate',
        'ADDONS' => 'addons',
        'STATUS' => 'status',
        'HASH' => 'hash',

        'NEXT_EVENT' => 'nextEvent',
        'LAST_EVENT' => 'lastEvent',
        'X_ATTRIBUTES' => 'xAttributes',
        'PAYPAL_URL' => 'paypalUrl'
    );
}
