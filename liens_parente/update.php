<?php

require_once "../src/config/check_permission.php";
require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $adherent1 = $_POST['adherent1'];
  $adherent2 = $_POST['adherent2'];
  $nature = $_POST['nature'];

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
    $sql = "UPDATE lien_parente
            SET nro_adherant_1 = ?, nro_adherant_2 = ?, nature = ?
            WHERE nro_adherant_1 = ?;
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$adherent1, $adherent2, $nature, $_POST["id"]]);

    // Redirect to the index page
    header('Location: index.php?id=' . $_POST["id"]);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}