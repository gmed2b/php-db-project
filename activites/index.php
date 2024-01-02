<?php

require_once "../src/init.php";

// Get all activites
$ACTIVITES = $db->query("SELECT * FROM activites")->fetchAll();

?>

<!doctype html>
<html>

<head>
  <?php require_once "../src/components/head.php" ?>
  <link rel="stylesheet" href="../src/css/modal.css">
</head>

<body class="bg-zinc-100">
  <?php include("../src/components/header.php") ?>

  <main class="container mx-auto mt-10 px-4">

    <div class="mb-14">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-3xl font-semibold">Liste des activités</h2>
      </div>

      <div class="overflow-auto rounded-lg shadow-md">
        <table class="w-full">
          <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">N° Activité</th>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">Code section</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Libellé</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Description</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Jour & Horaire</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Durée séance</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Lieu</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Capacité</th>
              <th class="w-24"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($ACTIVITES as $_activite) : ?>
            <tr class="bg-white even:bg-gray-50">
              <td class="p-3 text-sm text-gray-700">
                <a href="/project-db/activites/?id=<?= $_activite["nro_activite"] ?>"
                  class="font-bold text-blue-500 hover:underline">
                  #<?= $_activite["nro_activite"] ?>
                </a>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                <a href="/project-bd/sections/?section=<?= $_activite["code_section"] ?>"
                  class="font-bold text-blue-500 hover:underline">
                  <?= $_activite["code_section"] ?>
                </a>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_activite["libelle"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_activite["description"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                <?= $_activite["jour"] ?> <?= $_activite["horaire"] ?>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                <?php
                  $duree_seance = $_activite["duree_seance"];
                  $hours = floor($duree_seance / 60);
                  $minutes = $duree_seance % 60;
                  echo $hours . "h " . $minutes . "min";
                ?>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_activite["lieu"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_activite["capacite"] ?></td>
              <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                <a href="/project-bd/activites/?id=<?= $_activite["nro_activite"] ?>"
                  class="font-bold text-blue-500 hover:underline">
                  Modifier
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if (isset($_GET["id"]) && !empty($_GET["id"])) : ?>

    <div class="mb-14">
      <h2 class="text-3xl font-semibold mb-4">Activité #<?= $_GET["id"] ?></h2>

      <?php
          $sql = "SELECT * FROM activites WHERE nro_activite = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["id"]]);
          $CURR_ACTIVITE = $stmt->fetch();

          $rows = [
            [
              "Libellé" => [
                "name" => "libelle",
                "value" => $CURR_ACTIVITE["libelle"],
              ],
              "Description" => [
                "name" => "description",
                "value" => $CURR_ACTIVITE["description"],
              ],
            ],
            [
              "Lieu" => [
                "name" => "lieu",
                "value" => $CURR_ACTIVITE["lieu"],
              ],
              "Adresse" => [
                "name" => "adresse",
                "value" => $CURR_ACTIVITE["adresse"],
              ],
            ],
            [
              "Jour" => [
                "name" => "jour",
                "value" => $CURR_ACTIVITE["jour"],
              ],
              "Horaire" => [
                "name" => "horaire",
                "value" => $CURR_ACTIVITE["horaire"],
                "type" => "time",
              ],
            ],
            [
              "Durée séance" => [
                "name" => "duree_seance",
                "value" => $CURR_ACTIVITE["duree_seance"],
              ],
              "Capacité" => [
                "name" => "capacite",
                "value" => $CURR_ACTIVITE["capacite"],
              ],
            ]
          ];
        ?>
      <form action="update.php" method="post">
        <input type="hidden" name="nro_activite" value="<?= $CURR_ACTIVITE["nro_activite"] ?>">
        <div class="flex flex-col gap-4 bg-white p-4 shadow rounded">
          <?php foreach ($rows as $row) : ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach ($row as $key => $value) : ?>
            <div class="grid grid-cols-4 items-center">
              <label class="text-gray-500 font-bold pr-4" for="f-<?= $value["name"] ?>">
                <?= $key ?>
              </label>
              <div class="col-span-3">
                <input type="<?= isset($value["type"]) ? $value["type"] : "text" ?>" id="f-<?= $value["name"] ?>"
                  name="<?= $value["name"] ?>" value="<?= $value["value"] ?>"
                  class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight appearance-none focus:outline-none focus:bg-white focus:border-blue-500">
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>

          <button type="submit"
            class="self-start bg-green-600 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-green-700 hover:border-green-500 rounded">
            Enregistrer
          </button>
        </div>
      </form>

    </div>

    <?php endif; ?>

    <div id="modal-overlay"></div>
  </main>

  <script type="module" src="../src/js/modal.js"></script>
</body>

</html>