<?php

require_once "../src/config/check_permission.php";
require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_naissance = $_POST['date_naissance'];

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
    $sql = "INSERT INTO adherants
            (nom, prenom, date_naissance)
            VALUES (?, ?, ?);
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom, $prenom, $date_naissance]);

    // Redirect to the index page
    header('Location: index.php');
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}