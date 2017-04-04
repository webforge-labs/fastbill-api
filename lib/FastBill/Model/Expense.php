<?php

namespace FastBill\Model;

class Expense extends AbstractModel
{
    protected static $xmlProperties = array(
        'ORGANIZATION' => 'organization',

        'INVOICE_ID' => 'invoiceId',
        'INVOICE_DATE' => 'invoiceDate',
        'INVOICE_NUMBER' => 'invoiceNumber',
        'DUE_DATE' => 'dueDate',
        'COMMENT' => 'comment',

        'PAID_DATE'=>'paidDate',

        'CURRENCY_CODE' => 'currencyCode',
        'SUB_TOTAL' => 'subTotal',
        'VAT_TOTAL' => 'vatTotal',
        'TOTAL' => 'total'
    );
}
