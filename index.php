<?php

if (!$_GET['go']){
    echo 'ожидание команды';
    return;
}

$params = [
  'message_type' => 'TYPE_ORDER_NEW',
  'order_number' => '202343234-0022-1',
  'order_id' => 35452597966,
  'uuid' => 'bf354adc-e404-480c-a037-cf865464f1d9', 
  'created_at' => "2026-04-07T10:27:55.955Z",
  'seller_id' => 7376,
];


$orderNumber = $params['order_number'];
$arParams = [];
$errors = '';
$queryUrl = 'https://b24.unite-it.ru/rest/16374/uwlezd1nbjsj3eyd/crm.deal.add.json';

$arParams = http_build_query([
    'fields' => [
        'TITLE' => "Заказ Ozon №{$orderNumber}", // <- Здесь используется order_number
        'STAGE_ID' => 'Новая',                     // Стадия "Новая" (для стандартной воронки)
        'CATEGORY_ID' => 4,                      // ID воронки (0 - стандартная)
        'CONTACT_ID' => 33149,
    ],
]);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_FAILONERROR => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $arParams,
));

curl_exec($curl);

if(curl_errno($curl)){
    $errors = curl_error($curl);
}

curl_close($curl);

if(!empty($errors)){
    echo 'При создании сделки возникли проблемки';
    throw new Exception($errors);
} else {
    echo 'сделка успешно создана';
}

?>