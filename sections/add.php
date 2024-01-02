<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $code_section = $_POST['code_section'];
  $nom = $_POST['nom'];
  $debut_saison = $_POST['debut_saison'];

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
    $sql = "INSERT INTO sections
            (code_section, nom, debut_saison)
            VALUES (?, ?, ?);
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$code_section, $nom, $debut_saison]);

    // Redirect to the index page
    header('Location: index.php');
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}