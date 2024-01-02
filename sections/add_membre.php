<?php

require_once "../src/config/check_permission.php";
require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $code_section = $_POST["code_section"];
  $nro_benevole = $_POST["nro_benevole"];
  $fonction = $_POST["fonction"];

  // Validate form data
  $errors = [];

  foreach ($_POST as $key => $value) {
    if (empty($_POST[$key])) {
      $errors[] = ucfirst($key) . ' is required';
    }
  }

  // If there are no validation errors, update the database
  if (empty($errors)) {

    // Prepare and execute the SQL query
    $sql = "INSERT INTO membres
            (code_section, nro_benevole, fonction)
            VALUES (?, ?, ?);
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$code_section, $nro_benevole, $fonction]);

    // Redirect to the index page
    header('Location: index.php?section=' . $code_section);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}