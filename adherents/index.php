<?php

require_once "../src/init.php";

// Get all adherants
$adherants = $db->query("SELECT * FROM adherants")->fetchAll();

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
        <h2 class="text-3xl font-semibold">Liste des adhérents</h2>
        <button data-modal-target="#add-adherant-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Nouveau
        </button>
      </div>

      <div class="overflow-auto rounded-lg shadow-md">
        <table class="w-full">
          <thead class="bg-gray-300 border-b-2 border-gray-400">
            <tr>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">N° Adhérent</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Nom</th>
              <th class="w-36 p-3 text-sm font-bold tracking-wide text-left">Prénom</th>
              <th class="w-32 p-3 text-sm font-bold tracking-wide text-left">Téléphone</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Email</th>
              <th class="p-3 text-sm font-bold tracking-wide text-left">Nbre d'inscription</th>
              <th class="w-24"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($adherants as $_adherant) : ?>
            <?php 
            $sql = "SELECT COUNT(*) FROM inscrits WHERE nro_adherant = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$_adherant["nro_adherant"]]);
            $nb_inscriptions = $stmt->fetchColumn();
            ?>
            <tr class="bg-white even:bg-gray-50">
              <td class="p-3 text-sm text-gray-700">
                <a href="?id=<?= $_adherant["nro_adherant"] ?>" class="font-bold text-blue-500 hover:underline">
                  #<?= $_adherant["nro_adherant"] ?>
                </a>
              </td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_adherant["nom"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_adherant["prenom"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_adherant["telephone"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $_adherant["email"] ?></td>
              <td class="p-3 text-sm text-gray-700 whitespace-nowrap"><?= $nb_inscriptions ?></td>
              <td class="p-3 pr-5 text-sm text-gray-700 whitespace-nowrap text-end">
                <a href="?id=<?= $_adherant["nro_adherant"] ?>" class="font-bold text-blue-500 hover:underline">
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
      <h2 class="text-3xl font-semibold mb-4">Adhérent #<?= $_GET["id"] ?></h2>

      <?php
          $sql = "SELECT * FROM adherants WHERE nro_adherant = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["id"]]);
          $CURR_ADHERENT = $stmt->fetch();

          $rows = [
            [
              "Nom" => [
                "name" => "nom",
                "value" => $CURR_ADHERENT["nom"],
              ],
              "Prénom" => [
                "name" => "prenom",
                "value" => $CURR_ADHERENT["prenom"],
              ],
            ],
            [
              "Date de naissance" => [
                "name" => "date_naissance",
                "value" => $CURR_ADHERENT["date_naissance"],
                "type" => "date",
              ],
              "Téléphone" => [
                "name" => "telephone",
                "value" => $CURR_ADHERENT["telephone"],
                "type" => "tel"
              ],
            ],
            [
              "Email" => [
                "name" => "email",
                "value" => $CURR_ADHERENT["email"],
                "type" => "email"
              ],
              "Adresse" => [
                "name" => "adresse",
                "value" => $CURR_ADHERENT["adresse"],
              ],
            ],
            [
              "Code postal" => [
                "name" => "code_postal",
                "value" => $CURR_ADHERENT["code_postal"],
              ],
              "Ville" => [
                "name" => "ville",
                "value" => $CURR_ADHERENT["ville"],
              ],
            ]
          ];
        ?>
      <form action="update.php" method="post">
        <input type="hidden" name="nro_adherant" value="<?= $CURR_ADHERENT["nro_adherant"] ?>">
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

    <div class="mb-14">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-3xl font-semibold">Inscriptions</h2>
        <button data-modal-target="#inscription-modal"
          class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Inscrire
        </button>
      </div>
      <?php
          // Selectionne toutes les activités auxquelles l'adhérant est inscrit
          // tables: adherants, activites, inscrits
          $sql = "SELECT
            a.nro_adherant, i.date_inscription, ac.libelle, s.nom, s.logo
          FROM adherants a
          INNER JOIN inscrits i ON a.nro_adherant = i.nro_adherant
          INNER JOIN activites ac ON i.nro_activite = ac.nro_activite
          INNER JOIN sections s ON ac.code_section = s.code_section
          WHERE a.nro_adherant = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$_GET["id"]]);
          $inscriptions = $stmt->fetchAll();
        ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <?php if (empty($inscriptions)) : ?>
        <p class="text-gray-400 text-center">Cet adhérent n'est inscrit à aucune activité.</p>
        <?php endif; ?>

        <?php foreach ($inscriptions as $inscription) : ?>
        <div class="p-4 bg-zinc-300 rounded-2xl shadow">
          <div class="grid grid-cols-2">
            <div class="flex flex-col items-center gap-2">
              <img src="<?= $inscription["logo"] ?>" width="80" alt="section-logo">
              <p class="font-semibold"><?= $inscription["nom"] ?></p>
              <p class="text-sm">
                Adhésion : <?= (new DateTime($inscription["date_inscription"]))->format("d/m/Y") ?>
              </p>
            </div>
            <div class="flex flex-col items-center gap-2 px-4">
              <div class="text-center">
                <h2 class="text-base font-semibold underline mb-0">Carte d'Adhérent</h2>
                <p class="text-sm">Activité : <?= $inscription["libelle"] ?></p>
              </div>
              <div>
                <ul>
                  <li class="grid grid-cols-7">
                    <span class="font-semibold col-span-3">Numéro</span>
                    <span class="pl-2">:</span>
                    <span class="col-span-3">
                      <?= $inscription["nro_adherant"] ?>
                    </span>
                  </li>
                  <li class="grid grid-cols-7">
                    <span class="font-semibold col-span-3">Nom</span>
                    <span class="pl-2">:</span>
                    <span class="col-span-3">
                      <?= $CURR_ADHERENT["nom"] ?>
                    </span>
                  </li>
                  <li class="grid grid-cols-7">
                    <span class="font-semibold col-span-3">Prénom</span>
                    <span class="pl-2">:</span>
                    <span class="col-span-3">
                      <?= $CURR_ADHERENT["prenom"] ?>
                    </span>
                  </li>
                  <li class="grid grid-cols-7">
                    <span class="font-semibold col-span-3">Âge</span>
                    <span class="pl-2">:</span>
                    <span class="col-span-3">
                      <?php
                          $dob = new DateTime($CURR_ADHERENT["date_naissance"]);
                          $today = new DateTime();
                          $age = $today->diff($dob)->y;
                          echo $age . " ans";
                        ?>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      </div>


    </div>

    <?php endif; ?>

    <?php include("add_adherent_modal.php") ?>
    <?php include("inscription_modal.php") ?>

    <div id="modal-overlay"></div>
  </main>

  <script type="module" src="../src/js/modal.js"></script>
</body>

</html>