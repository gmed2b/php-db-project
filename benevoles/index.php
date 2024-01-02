<?php

require_once "../src/init.php";

// Get all benevoles
$benevoles = $db->query("SELECT * FROM benevoles")->fetchAll();

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
        <h2 class="text-3xl font-semibold">Liste des bénévoles</h2>
        <button data-modal-target="#add-benevole-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Nouveau
        </button>
      </div>

      <div class="overflow-auto rounded-lg shadow-md">
        <table class="w-full">
          <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">N° Bénévole</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Nom</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Prénom</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Téléphone</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Email</th>
              <th class="w-24"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($benevoles as $_benevole) : ?>
            <tr class="bg-white even:bg-gray-50">
              <td class="p-3 text-sm text-gray-700">
                <a href="?id=<?= $_benevole["nro_benevole"] ?>" class="font-bold text-blue-500 hover:underline">
                  #<?= $_benevole["nro_benevole"] ?>
                </a>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_benevole["nom"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_benevole["prenom"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_benevole["telephone"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_benevole["email"] ?></td>
              <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                <a href="?id=<?= $_benevole["nro_benevole"] ?>" class="font-bold text-blue-500 hover:underline">
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
      <h2 class="text-3xl font-semibold mb-4">Bénévole #<?= $_GET["id"] ?></h2>

      <?php
          $sql = "SELECT * FROM benevoles WHERE nro_benevole = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["id"]]);
          $CURR_BENEVOLE = $stmt->fetch();

          $rows = [
            [
              "Nom" => [
                "name" => "nom",
                "value" => $CURR_BENEVOLE["nom"],
              ],
              "Prénom" => [
                "name" => "prenom",
                "value" => $CURR_BENEVOLE["prenom"],
              ],
            ],
            [
              "Email" => [
                "name" => "email",
                "value" => $CURR_BENEVOLE["email"],
                "type" => "email"
              ],
              "Téléphone" => [
                "name" => "telephone",
                "value" => $CURR_BENEVOLE["telephone"],
                "type" => "tel"
              ],
            ]
          ];
        ?>
      <form action="update.php" method="post">
        <input type="hidden" name="nro_benevole" value="<?= $CURR_BENEVOLE["nro_benevole"] ?>">
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

    <?php include("add_benevole_modal.php") ?>

    <div id="modal-overlay"></div>
  </main>

  <script type="module" src="../src/js/modal.js"></script>
</body>

</html>