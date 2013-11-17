<?php

namespace FastBill\Model;

class Project extends AbstractModel {

  protected static $xmlProperties = array(
    'PROJECT_ID'=>'projectId',
    'PROJECT_NAME'=>'projectName',
    'CUSTOMER_ID'=>'customerId',
    'CUSTOMER_COSTCENTER_ID'=>'customerCostcenterId',
    'HOUR_PRICE'=>'hourPrice',
    'CURRENCY_CODE'=>'currencyCode',
    'VAT_PERCENT'=>'vatPercent',
    'START_DATE'=>'startDate',
    'END_DATE'=>'endDate',
  );
}
