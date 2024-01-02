<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
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
    $sql = "UPDATE membres
            SET fonction = ?
            WHERE nro_benevole = ? AND code_section = ?;
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$fonction, $nro_benevole, $_GET["section"]]);

    // Redirect to the index page
    header('Location: index.php?section=' . $_GET["section"]);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}