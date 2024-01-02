<?php

require_once "../src/config/check_permission.php";
require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_naissance = $_POST['date_naissance'];
  $telephone = $_POST['telephone'];
  $email = $_POST['email'];
  $adresse = $_POST['adresse'];
  $code_postal = $_POST['code_postal'];
  $ville = $_POST['ville'];

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
    $sql = "UPDATE adherants
            SET nom = ?, prenom = ?, telephone = ?, email = ?, adresse = ?, code_postal = ?, ville = ?
            WHERE nro_adherant = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $code_postal, $ville, $_POST['nro_adherant']]);

    // Redirect to the index page
    header('Location: index.php?id=' . $_POST['nro_adherant']);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}