<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $telephone = $_POST['telephone'];
  $email = $_POST['email'];

  // Validate form data
  $errors = [];

  foreach ($_POST as $key => $value) {
    if (empty($_POST[$key])) {
      $errors[] = ucfirst($key) . ' is required';
    }
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
  }

  // If there are no validation errors, update the database
  if (empty($errors)) {

    // Prepare and execute the SQL query
    $sql = "UPDATE benevoles
            SET nom = ?, prenom = ?, telephone = ?, email = ?
            WHERE nro_benevole = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom, $prenom, $telephone, $email, $_POST['nro_benevole']]);

    // Redirect to the index page
    header('Location: index.php?id=' . $_POST['nro_benevole']);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}