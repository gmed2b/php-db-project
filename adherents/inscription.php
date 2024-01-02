<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nro_adherant = $_POST['nro_adherant'];
  $nro_activite = $_POST['activite'];

  // Validate form data
  $errors = [];

  foreach ($_POST as $key => $value) {
    if (empty($_POST[$key])) {
      $errors[] = ucfirst($key) . ' is required';
    }
  }

  // If there are no validation errors, update the database
  if (empty($errors)) {

    // Check if the adherent is already registered to the activity
    $sql = "SELECT * FROM inscrits WHERE nro_adherant = ? AND nro_activite = ?;";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nro_adherant, $nro_activite]);
    $already_registered = $stmt->fetch();

    if ($already_registered) {
      echo "L'adhérent est déjà inscrit à cette activité";
      exit;
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO inscrits
            (nro_adherant, nro_activite, date_inscription)
            VALUES (?, ?, NOW());
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nro_adherant, $nro_activite]);

    // Redirect to the index page
    header('Location: index.php?id=' . $nro_adherant);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}