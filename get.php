<?php
require_once 'connection.php';

$params = [
  'message_type' => 'TYPE_ORDER_NEW',
  'order_number' => '202343234-0022-1',
  'order_id' => 35452597966,
  'uuid' => 'bf354adc-e404-480c-a037-cf865464f1d9', 
  'created_at' => "2026-04-07T10:27:55.955Z",
  'seller_id' => 7376,
];


$rawPayload = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($rawPayload === false) {
    die('json_encode error: ' . json_last_error_msg());
}

$postingId = (string)$params['order_id'];

$sql = "INSERT INTO `Заказ` (Posting_id, raw_payload) VALUES (?, ?)";

$stmt = $connect->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $connect->error);
}

$stmt->bind_param('ss', $postingId, $rawPayload);

if ($stmt->execute()) {
    echo "OK, insert_id = " . $stmt->insert_id;
} else {
    echo "Execute failed: " . $stmt->error;
}

$stmt->close();
$connect->close();


?>
