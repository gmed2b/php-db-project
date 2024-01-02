<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $libelle = $_POST['libelle'];
  $description = $_POST['description'];
  $jour = $_POST['jour'];
  $horaire = $_POST['horaire'];
  $duree_seance = $_POST['duree_seance'];
  $tarif_annuel = $_POST['tarif_annuel'];
  $lieu = $_POST['lieu'];
  $capacite = $_POST['capacite'];
  $adresse = $_POST['adresse'];

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
    $sql = "UPDATE activites
            SET libelle = ?, description = ?, jour = ?, horaire = ?, duree_seance = ?, tarif_annuel = ?, lieu = ?, capacite = ?, adresse = ?
            WHERE nro_activite = ?;
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$libelle, $description, $jour, $horaire, $duree_seance, $tarif_annuel, $lieu, $capacite, $adresse, $_POST['nro_activite']]);

    // Redirect to the index page
    header('Location: index.php?id=' . $_POST['nro_activite']);
    
  } else {
    // Display validation errors
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  }

}