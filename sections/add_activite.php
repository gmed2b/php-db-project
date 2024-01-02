<?php

require_once "../src/config/check_permission.php";
require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $code_section = $_POST["code_section"];
  $libelle = $_POST["libelle"];
  $description = $_POST["description"];

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
    $sql = "INSERT INTO activites
            (code_section, libelle, description)
            VALUES (?, ?, ?);
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$code_section, $libelle, $description]);

    // Redirect to the index page
    header('Location: index.php?section=' . $code_section);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}