<?php

namespace FastBill\Model;

class Invoice extends AbstractModel {

  protected static $xmlProperties = array(
    'INVOICE_ID'=>'invoiceId',
    'TYPE'=>'type',
    'CUSTOMER_ID'=>'customerId',
    'CUSTOMER_NUMBER'=>'customerNumber',
    'CUSTOMER_COSTCENTER_ID'=>'customerCostcenterId',
    'PROJECT_ID'=>'projectId',


    'FIRST_NAME'=>'firstName',
    'LAST_NAME'=>'lastName',

/*
    'ORGANIZATION'=>'organization',
    'SALUTATION'=>'salutation',
    'ADDRESS'=>'address',
    'ADDRESS_2'=>'address2',
    'ZIPCODE'=>'zipcode',
    'CITY'=>'city',
    'PAYMENT_TYPE'=>'paymentType',
    'BANK_NAME'=>'bankName',
    'BANK_ACCOUNT_NUMBER'=>'bankAccountNumber',
    'BANK_CODE'=>'bankCode',
    'BANK_ACCOUNT_OWNER'=>'bankAccountOwner',
    'COUNTRY_CODE'=>'countryCode',
    'VAT_ID'=>'vatId',
*/    
    'CURRENCY_CODE'=>'currencyCode',    
    'TEMPLATE_ID'=>'templateId',
    'INVOICE_NUMBER'=>'invoiceNumber',
    'INVOICE_TITLE'=>'title',
    'INTROTEXT'=>'introtext',
    'PAID_DATE'=>'paidDate',
    'IS_CANCELED'=>'isCanceled',
    'INVOICE_DATE'=>'invoiceDate',
    'DUE_DATE'=>'dueDate',
    'DELIVERY_DATE'=>'deliveryDate',
    'CASH_DISCOUNT_PERCENT'=>'cashDiscountPercent',
    'CASH_DISCOUNT_DAYS'=>'cashDiscountDays',
    'ITEMS'=>'items',
    'DOCUMENT_URL'=>'documentUrl',

    'VAT_ITEMS'=>'vatItems', // ???
    'SUB_TOTAL'=>'subTotal',
    'VAT_TOTAL'=>'vatTotal',
    'TOTAL'=>'total',
  );

  protected $collections = array('items'=>'FastBill\Model\InvoiceItem');

}
