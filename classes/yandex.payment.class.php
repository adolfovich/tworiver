<?php


class Payment extends YandexCheckout\Client
{
  protected $db;

  function __construct($db, $client, $shop_id, $secret_key)
  {
    $this->db = $db;
    $this->client = $client;

    $this->shop_id = $shop_id;
    $this->secret_key = $secret_key;

  }

  public function getPaymentUrl($user, $pay_variant, $amount)
  {

    

    return $url;
  }
}
