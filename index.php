<?php
require_once 'connection.php';
require 'get.php';

if (!isset($_GET['go'])) {
    echo 'ожидание команды';
    return;
}

function b24_call($queryUrl, $arParams) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $arParams,
    ]);

    $out = curl_exec($curl); 
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return ['error' => $error, 'http_code' => $httpCode];
    }

    curl_close($curl);
    return json_decode($out, true);
}

function checkOrderInDatabase($connect, $orderId) {
    $stmt = $connect->prepare("SELECT id FROM ozon_orders WHERE order_id = ? LIMIT 1");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }
    
    return false;
}


$params = [
    'message_type' => 'TYPE_ORDER_NEW',
    'order_number' => '202343234-0022-3',
    'order_id'     => 35452597968,
    'uuid'         => 'bf354adc-e404-480c-a037-cf865464f1d9',
    'created_at'   => '2026-04-07T10:27:55.955Z',
    'seller_id'    => 7376,
];

$orderNumber = $params['order_number'];
$orderId = $params['order_id'];
$queryUrl = 'https://b24.unite-it.ru/rest/16374/uwlezd1nbjsj3eyd/crm.deal.add.json';

try {
    $insertResult = insert_into_db($connect, $params);
    
    if (is_array($insertResult) && isset($insertResult['error'])) {
        echo 'Ошибка сохранения в БД: ' . $insertResult['error'];
        exit;
    }
    
    echo "Запись сохранена в БД, insert_id = {$insertResult}<br>";
    
} catch (mysqli_sql_exception $e) {
    // Код 1062 = Duplicate entry
    if ($e->getCode() == 1062) {
        echo "Заказ #{$orderId} уже существует (дубликат). Номер заказа {$orderNumber}.<br>";
        exit;
    }
    // Другие ошибки БД
    echo 'Ошибка БД: ' . $e->getMessage();
    exit;
}

$arParams = http_build_query([
    'fields' => [
        'TITLE'       => "Заказ Ozon №{$orderNumber}",
        'STAGE_ID'    => 'C4:NEW', 
        'CATEGORY_ID' => 4,
        'CONTACT_ID'  => 33149,
    ],
]);


if (isset($response['error'])) {
    echo 'Ошибка cURL: ' . $response['error'];
} elseif (isset($response['result'])) {
    echo "Сделка создана, ID = {$response['result']}";
} else {
    echo 'Ошибка Битрикс24: ' . json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>