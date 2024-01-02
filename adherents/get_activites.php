<?php

require_once "../src/init.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $section = $_POST['section'];

  $sql = "SELECT
            a.nro_activite,
            a.libelle,
            (a.capacite - COUNT(i.nro_adherant)) > 0 AS places_disponibles
          FROM activites a
          LEFT JOIN inscrits i ON a.nro_activite = i.nro_activite
          WHERE a.code_section = ?
          GROUP BY a.nro_activite;";

  $all_activites = $db->prepare($sql);
  $all_activites->execute([$section]);
  $all_activites = $all_activites->fetchAll();

  echo json_encode($all_activites);

}