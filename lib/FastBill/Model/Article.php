<?php

namespace FastBill\Model;

class Article extends AbstractModel {

  protected static $xmlProperties = array(
    'ARTICLE_NUMBER'=>'articleNumber',
    'TITLE'=>'title',
    'DESCRIPTION'=>'description',
    'TAGS'=>'tags',

    'UNIT_PRICE'=>'unitPrice',
    'SETUP_FEE'=>'setupFee',
    'ALLOW_MULTIPLE'=>'allowMultiple',
    'IS_ADDON'=>'isAddon',
    'CURRENCY_CODE'=>'currencyCode',
    'VAT_PERCENT'=>'vatPercent',
    'SUBSCRIPTION_INTERVAL'=>'subscriptionInterval',
    'SUBSCRIPTION_NUMBER_EVENTS'=>'subscriptionNumberEvents',
    'SUBSCRIPTION_TRIAL'=>'subscriptionTrial',
    'SUBSCRIPTION_DURATION'=>'subscriptionDuration',
    'SUBSCRIPTION_DURATION_FOLLOW'=>'subscriptionDurationFollow',
    'SUBSCRIPTION_CANCELLATION'=>'subscriptionCancellation',

    'RETURN_URL_SUCCESS'=>'returnUrlSuccess',
    'RETURN_URL_CANCEL'=>'returnUrlCancel',
    'CHECKOUT_URL'=>'checkoutUrl'
  );
}
