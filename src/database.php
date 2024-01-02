<?php

// Set DSN
$dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};";
$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

try {
  $db = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
}
catch (PDOException $e) {
  $error = $e->getMessage();
  die('Database error : '. $error);
}