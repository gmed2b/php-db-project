<?php

require_once "../src/init.php";

$liens_parente = $db->query("SELECT * FROM lien_parente;")->fetchAll();

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
        <h2 class="text-3xl font-semibold">Lien de parenté</h2>
        <button data-modal-target="#add-lien-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Nouveau
        </button>
      </div>

      <div class="overflow-auto rounded-lg shadow-md">
        <table class="w-full">
          <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">Adhérent 1</th>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">Adhérent 2</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Nature</th>
              <th class="w-24"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($liens_parente as $_lien) : ?>
            <?php 
            $sql = "SELECT CONCAT(nom,' ',prenom) as nom FROM adherants WHERE nro_adherant = ?";
            $stmt = $db->prepare($sql);
            $stmt2 = $db->prepare($sql);
            $stmt->execute([$_lien["nro_adherant_1"]]);
            $stmt2->execute([$_lien["nro_adherant_2"]]);
            $nom1 = $stmt->fetchColumn();
            $nom2 = $stmt2->fetchColumn();
            ?>
            <tr class="bg-white even:bg-gray-50">
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $nom1 ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $nom2 ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_lien["nature"] ?></td>
              <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                <a href="?id=<?= $_lien["nro_adherant_1"] ?>" class="font-bold text-blue-500 hover:underline">
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
      <h2 class="text-3xl font-semibold mb-4">Modification</h2>

      <?php
        $sql = "SELECT nro_adherant, CONCAT(nom,' ',prenom) as nom FROM adherants";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $adherents = $stmt->fetchAll();
      
        $sql = "SELECT * FROM lien_parente WHERE nro_adherant_1 = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_GET["id"]]);
        $CURR_LIEN = $stmt->fetch();
      ?>
      <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
        <div class="flex flex-col gap-4 bg-white p-4 shadow rounded">
          <div class="grid grid-cols-1 gap-2">
            <div class="grid grid-cols-4 items-center">
              <label class="text-gray-500 font-bold pr-4" for="fm-adherent1">
                Adherent 1 </label>
              <div class="col-span-3">
                <select id="fm-adherent1" name="adherent1"
                  class="w-full p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                  <option selected>---</option>
                  <?php foreach ($adherents as $_adherent) : ?>
                  <option value="<?= $_adherent["nro_adherant"] ?>"
                    <?= $_adherent["nro_adherant"] == $CURR_LIEN["nro_adherant_1"] ? "selected" : "" ?>>
                    <?= $_adherent["nom"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-4 items-center">
              <label class="text-gray-500 font-bold pr-4" for="fm-adherent2">
                Adherent 2 </label>
              <div class="col-span-3">
                <select id="fm-adherent2" name="adherent2"
                  class="w-full p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                  <option selected>---</option>
                  <?php foreach ($adherents as $_adherent) : ?>
                  <option value="<?= $_adherent["nro_adherant"] ?>"
                    <?= $_adherent["nro_adherant"] == $CURR_LIEN["nro_adherant_2"] ? "selected" : "" ?>>
                    <?= $_adherent["nom"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-4 items-center">
              <label class="text-gray-500 font-bold pr-4" for="fm-nature">
                Nature </label>
              <div class="col-span-3">
                <input type="text" id="fm-nature" name="nature" value="<?= $CURR_LIEN["nature"] ?>" class=" w-full p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight appearance-none
                  focus:outline-none focus:bg-white focus:border-blue-500">
              </div>
            </div>
          </div>

          <button type="submit"
            class="self-start bg-green-600 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-green-700 hover:border-green-500 rounded">
            Enregistrer
          </button>
        </div>
      </form>

    </div>

    <?php endif; ?>

    <?php include("add_lien_modal.php") ?>

    <div id="modal-overlay"></div>
  </main>

  <script type="module" src="../src/js/modal.js"></script>
</body>

</html>