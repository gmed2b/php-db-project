<?php

require_once "../src/init.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $nro_benevole = $_POST["nro_benevole"];
  $section = $_POST["section"];

  // Prepare and execute the SQL query
  $sql = "DELETE FROM membres
          WHERE nro_benevole = ? AND code_section = ?;
  ";
  $stmt = $db->prepare($sql);
  $stmt->execute([$nro_benevole, $section]);

  echo json_encode([
    "success" => true,
  ]);

}