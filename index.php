<?php

require_once "src/init.php";

// Get activities count
$activities_count = $db->query("SELECT count(nro_activite) FROM activites")->fetchColumn();

// Get adherants count
$adherants_count = $db->query("SELECT count(nro_adherant) FROM adherants")->fetchColumn();

?>

<!doctype html>
<html>

<head>
  <?php require_once "src/components/head.php" ?>
</head>

<body class="bg-zinc-100">
  <?php include("src/components/header.php") ?>

  <main class="container mx-auto px-5">

    <div class="mt-8">
      <h1 class="text-3xl font-semibold mb-6">Tableau de bord</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-4 shadow rounded">
          <h2 class="text-xl font-semibold mb-4">Statistiques principales</h2>
          <div class="flex justify-between mb-2">
            <div>
              <p class="text-lg font-semibold">Nombre d'adhérents</p>
              <p>
                <?= $adherants_count ?>
              </p>
            </div>
            <div>
              <p class="text-lg font-semibold">Nombre d'activités</p>
              <p>
                <?= $activities_count ?>
              </p>
            </div>
          </div>
        </div>
        <div class="bg-white p-4 shadow rounded">
          <h2 class="text-xl font-semibold mb-4">Informations importantes</h2>
          <div>
            <p class="text-lg font-semibold mb-2">Prochaine réunion des bénévoles</p>
            <p>21 janvier 2024</p>
          </div>
        </div>
      </div>

      <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">Graphiques / Autres informations</h2>
        <div class="bg-white p-4 shadow rounded">
          <h3 class="text-xl font-semibold mb-4">Répartition des activités par type</h3>
          <canvas id="activitesChart" width="400" height="200"></canvas>
        </div>
      </div>

  </main>

  <!-- Scripts JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
  <script src="src/js/dashboard.js"></script>
</body>

</html>