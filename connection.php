<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "Ozon_orders";
    $connect = new mysqli($servername, $username, $password, $db_name, 3306);
    if($connect->connect_error){
        die("Connection failed".$connect->connect_error);
    }
    echo "Connection Successful";


    // Важно для корректной работы с русскими буквами и JSON
    $connect->set_charset('utf8mb4');
    ?>