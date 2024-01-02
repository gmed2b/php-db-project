<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];

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
    $sql = "INSERT INTO benevoles
            (nom, prenom)
            VALUES (?, ?);
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom, $prenom]);

    // Redirect to the index page
    header('Location: index.php');
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}