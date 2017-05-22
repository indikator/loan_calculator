<?php

require_once 'db.php';
$config = require_once('config.php');
$db = new Db($config);
$connection = $db->getConnection();
$sql = "
        CREATE TABLE IF NOT EXISTS calculate_request (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            date DATETIME NOT NULL,
            loan_amount FLOAT NOT NULL,
            loan_period INT NOT NULL,
            interest_rate FLOAT NOT NULL,
            first_payment DATE NOT NULL
        )
    ";
$connection->exec($sql);

$sql = "
        CREATE TABLE IF NOT EXISTS payment_calendar (
            request_id INT NOT NULL,
            payment_number INT NOT NULL,
            payment_date DATE NOT NULL,
            principal_debt FLOAT NOT NULL,
            interest FLOAT NOT NULL,
            total_amount FLOAT NOT NULL,
            remaining_debt FLOAT NOT NULL,
            FOREIGN KEY (request_id) REFERENCES calculate_request(id)
        )
    ";
$connection->exec($sql);
