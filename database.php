<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', 'Admin@123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $pdo;