<?php
require_once 'connection.php';

$sql = "SELECT * FROM `Заказ`";

$result = $connect->query($sql);

if (!$result) {
    die('Запрос обвалился :( : ' . $connect->error);
}

if ($result->num_rows === 0) {
    echo "Заказов пока нет.";
    $connect->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы Ozon</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; }
        pre { margin: 0; white-space: pre-wrap; word-break: break-word; max-width: 600px; }
    </style>
</head>
<body>

<h1>Заказы Ozon</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Posting_id</th>
            <th>raw_payload</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Posting_id']) ?></td>
                <td>
                    <pre><?= htmlspecialchars(
                        json_encode(
                            json_decode($row['raw_payload'], true),
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        )
                    ) ?></pre>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


</body>
</html>
