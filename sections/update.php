<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = $_POST["nom"];
  $debut_saison = $_POST["debut_saison"];
  $logo = $_POST["logo"];
  $nro_referent = $_POST["nro_referent"];
  if ($nro_referent == "-1") {
    $nro_referent = null;
  }

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
    $sql = "UPDATE sections
            SET nom = ?, debut_saison = ?, logo = ?, nro_referent = ?
            WHERE code_section = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom, $debut_saison, $logo, $nro_referent, $_POST['code_section']]);

    // Redirect to the index page
    header('Location: index.php?section=' . $_POST['code_section']);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}