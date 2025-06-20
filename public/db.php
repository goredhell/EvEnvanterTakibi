<?php
$pdo = new PDO("mysql:host=db;dbname=envanter;charset=utf8mb4", "envanteruser", "envanterpass");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
