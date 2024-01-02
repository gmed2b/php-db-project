<?php

require_once "../src/init.php";

$sql = "SELECT
          s.code_section, s.nom, s.debut_saison,
          CONCAT(b.nom, ' ', b.prenom) as referent
        FROM sections s
        LEFT JOIN benevoles b ON s.nro_referent = b.nro_benevole;";
$SECTIONS = $db->query($sql)->fetchAll();

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
        <h2 class="text-3xl font-semibold">Liste des sections</h2>
        <button data-modal-target="#add-section-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Nouveau
        </button>
      </div>

      <div class="overflow-auto rounded-lg shadow-md">
        <table class="w-full">
          <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
              <th class="w-20 p-3 text-sm font-bold tracking-wide text-left">Code section</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Nom</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Début saison</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Référent</th>
              <th class="w-24"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($SECTIONS as $_section) : ?>
            <tr class="bg-white even:bg-gray-50">
              <td class="p-3 text-sm text-gray-700">
                <a href="?section=<?= $_section["code_section"] ?>" class="font-bold text-blue-500 hover:underline">
                  <?= $_section["code_section"] ?>
                </a>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_section["nom"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_section["debut_saison"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_section["referent"] ?? "---" ?></td>
              <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                <a href="?section=<?= $_section["code_section"] ?>" class="font-bold text-blue-500 hover:underline">
                  Modifier
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if (isset($_GET["section"]) && !empty($_GET["section"])) : ?>

    <div class="mb-14">
      <h2 class="text-3xl font-semibold mb-4">
        Section
        <span class="underline">
          <?= $_GET["section"] ?>
        </span>
      </h2>

      <?php
          $sql = "SELECT * FROM sections WHERE code_section = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["section"]]);
          $CURR_SECTION = $stmt->fetch();

          $sql = "SELECT 
                    b.nro_benevole,
                    CONCAT(b.nom, ' ', b.prenom) as nom
                  FROM membres m
                  JOIN benevoles b ON m.nro_benevole = b.nro_benevole
                  WHERE m.code_section = ?;";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["section"]]);
          $BENEVOLES = $stmt->fetchAll();
          
          $rows = [
            [
              "Nom" => [
                "name" => "nom",
                "value" => $CURR_SECTION["nom"],
              ],
              "Début saison" => [
                "name" => "debut_saison",
                "value" => $CURR_SECTION["debut_saison"],
                "type" => "date",
              ],
            ],
            [
              "Référent" => [
                "name" => "nro_referent",
                "value" => $CURR_SECTION["nro_referent"],
                "type" => "select",
                "options" => $BENEVOLES,
                "options_key" => "nro_benevole",
                "options_value" => "nom",
              ],
              "Logo" => [
                "name" => "logo",
                "value" => $CURR_SECTION["logo"],
              ],
            ],
          ];
        ?>
      <form action="update.php" method="post">
        <input type="hidden" name="code_section" value="<?= $CURR_SECTION["code_section"] ?>">
        <div class="flex flex-col gap-4 bg-white p-4 shadow rounded">
          <?php foreach ($rows as $row) : ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php foreach ($row as $key => $value) : ?>
            <div class="grid grid-cols-4 items-center">
              <label class="text-gray-500 font-bold pr-4" for="f-<?= $value["name"] ?>">
                <?= $key ?>
              </label>
              <div class="col-span-3">

                <?php if (isset($value["options"])) : ?>
                <select id="f-<?= $value["name"] ?>" name="<?= $value["name"] ?>"
                  class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                  <option value="-1" selected>Aucun</option>
                  <?php foreach ($value["options"] as $_option) : ?>
                  <option value="<?= $_option[$value["options_key"]] ?>"
                    <?= $_option[$value["options_key"]] == $value["value"] ? "selected" : "" ?>>
                    <?= $_option[$value["options_value"]] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <?php else : ?>
                <input type="<?= isset($value["type"]) ? $value["type"] : "text" ?>" id="f-<?= $value["name"] ?>"
                  name="<?= $value["name"] ?>" value="<?= $value["value"] ?>"
                  class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight appearance-none focus:outline-none focus:bg-white focus:border-blue-500">
                <?php endif; ?>

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

    <div class="mb-14">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-3xl font-semibold">Membres</h2>
        <button data-modal-target="#add-membre-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Ajouter
        </button>
      </div>
      <?php
        $sql = "SELECT
          b.nro_benevole, b.nom, b.prenom, 
          b.telephone, b.email, m.fonction
        FROM benevoles b
        JOIN membres m ON m.nro_benevole = b.nro_benevole
        WHERE m.code_section = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_GET["section"]]);
        $MEMBRES = $stmt->fetchAll();
      ?>
      <div class="mb-10">

        <?php if (empty($MEMBRES)) : ?>
        <p class="text-gray-400 text-center">Cette section ne dispose d'aucun membre.</p>
        <?php else : ?>

        <div class="overflow-auto rounded-lg shadow-md">
          <table class="w-full">
            <thead class="bg-gray-300 border-b-2 border-gray-400">
              <tr>
                <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">N° Bénévole</th>
                <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Nom</th>
                <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Prenom</th>
                <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Téléphone</th>
                <th class="w-44 p-3 text-sm font-bold tracking-wide text-left">Email</th>
                <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Fonction</th>
                <th class="w-24"></th>
                <!-- <th class="w-24"></th> -->
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php foreach ($MEMBRES as $_membre) : ?>
              <tr class="bg-white even:bg-gray-50">
                <td class="p-3 text-sm text-gray-700">
                  <a href="/project-bd/benevoles/?id=<?= $_membre["nro_benevole"] ?>"
                    class="font-bold text-blue-500 hover:underline">
                    #<?= $_membre["nro_benevole"] ?>
                  </a>
                </td>
                <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_membre["nom"] ?></td>
                <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_membre["prenom"] ?></td>
                <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_membre["telephone"] ?></td>
                <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_membre["email"] ?></td>
                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                  <span
                    class="p-1.5 text-xs font-semibold uppercase tracking-wide text-yellow-800 bg-yellow-200 rounded-lg bg-opacity-50">
                    <?= $_membre["fonction"] ?>
                  </span>
                </td>
                <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                  <span data-modal-target="#update-membre-modal"
                    onclick="update_membre(<?= $_membre['nro_benevole'] ?>, '<?= $_membre['fonction'] ?>')"
                    class="font-bold text-blue-500 hover:underline cursor-pointer">
                    Modifier
                  </span>
                </td>
                <!-- <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                  <span onclick="delete_membre(<?= $_membre['nro_benevole'] ?>, '<?= $_GET['section'] ?>')"
                    class="font-bold text-red-500 hover:underline cursor-pointer">
                    Supprimer
                  </span>
                </td> -->
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php endif; ?>

      </div>
    </div>

    <div class="mb-14">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-3xl font-semibold">Activités proposées</h2>
        <button data-modal-target="#add-activité-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Nouveau
        </button>
      </div>
      <?php
        $sql = "SELECT *
        FROM activites
        WHERE code_section = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_GET["section"]]);
        $ACTIVITES = $stmt->fetchAll();
      ?>
      <div class="mb-10">

        <?php if (empty($ACTIVITES)) : ?>
        <p class="text-gray-400 text-center">Cette section propose aucune activité.</p>
        <?php else : ?>

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

        <?php endif; ?>

      </div>

    </div>

    <?php endif; ?>

    <?php include("add_section_modal.php") ?>
    <?php include("add_membre_modal.php") ?>
    <?php include("update_membre_modal.php") ?>
    <?php include("add_activite_modal.php") ?>

    <div id="modal-overlay"></div>
  </main>

  <script type="module" src="../src/js/modal.js"></script>
  <script>
  function update_membre(nro_benevole, fonction) {
    const modal = document.querySelector("#update-membre-modal");
    modal.querySelector("input[name='nro_benevole']").value = nro_benevole;
    modal.querySelector("#fm-fonction").value = fonction;
  }

  async function delete_membre(nro_benevole, section) {
    if (confirm("Voulez-vous vraiment supprimer ce membre ?")) {
      const formData = new FormData();
      formData.append("nro_benevole", nro_benevole);
      formData.append("section", section);
      const res = await fetch("delete_membre.php", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      if (data.success) {
        location.reload();
      } else {
        alert(data.message);
      }
    }
  }
  </script>
</body>

</html>