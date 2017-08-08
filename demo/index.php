<?php 
require_once '../vendor/autoload.php';

$tk = new \Jsnlib\Token;

// 1. 加入使用鑰匙
$tk->key(uniqid('test132'));

// 2. 取得 Token 過期日
$expiry = $tk->expriy_date("Y-m-d H:i:s", "now", +1, "day");

// 3. 綁定編號，並加入過期日，可產生 Token 
$token = $tk->create('ABC', $expiry);


/**
 * Jsnlib\Ao 輸出格式如
 * value => '42d398f2ecef48da5b4dab7e63c3323a8894048357ce8ba319dbec7ea847cb99'
 * expiry => '2017-08-15'
 */
echo $token;

/**
 * 若要 JSON 格式如
 * array ( 'value' => '1a94b598b4067998b601221a47068cc31ac8fd6c3748abffec3e9046cd164cc5', 'expiry' => '2017-08-09 10:16:41', )
 */
echo ($tk->json($token));

die;
