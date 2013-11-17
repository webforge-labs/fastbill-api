<?php

namespace FastBill\Model;

class InvoiceItem extends AbstractModel {

  protected static $xmlProperties = array(
    'INVOICE_ITEM_ID'=>'invoiceItemId',
    'ARTICLE_NUMBER'=>'articleNumber',
    'DESCRIPTION'=>'description',
    'QUANTITY'=>'quantity',
    'UNIT_PRICE'=>'unitPrice',
    'UNIT'=>'unit',
    'VAT_PERCENT'=>'vatPercent',
    
    'VAT_VALUE'=>'vatValue',
    'COMPLETE_NET'=>'completeNet',
    'COMPLETE_GROSS'=>'completeGross',
    'SORT_ORDER'=>'sortOrder'
  );
}
