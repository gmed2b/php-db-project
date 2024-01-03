<?php

$sql = "SELECT nro_adherant, CONCAT(nom,' ',prenom) as nom FROM adherants";
$stmt = $db->prepare($sql);
$stmt->execute();
$adherents = $stmt->fetchAll();

?>
<div id="add-lien-modal" class="modal rounded-lg">
  <form action="add.php" method="post">
    <div class="relative bg-white rounded-lg shadow ">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
        <h3 class="text-xl font-semibold text-gray-900 ">
          Ajouter un lien
        </h3>
        <button type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
          data-close-modal>
          &times;
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <div class="p-4 md:p-5 space-y-4">
        <div class="grid grid-cols-1 gap-2">
          <div class="grid grid-cols-4 items-center">
            <label class="text-gray-500 font-bold pr-4" for="fm-adherent1">
              Adherent 1 </label>
            <div class="col-span-3">
              <select id="fm-adherent1" name="adherent1"
                class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                <option selected>Choisir adhérent 1</option>
                <?php foreach ($adherents as $_adherent) : ?>
                <option value="<?= $_adherent["nro_adherant"] ?>"><?= $_adherent["nom"] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-4 items-center">
            <label class="text-gray-500 font-bold pr-4" for="fm-adherent2">
              Adherent 2 </label>
            <div class="col-span-3">
              <select id="fm-adherent2" name="adherent2"
                class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                <option selected>Choisir adhérent 2</option>
                <?php foreach ($adherents as $_adherent) : ?>
                <option value="<?= $_adherent["nro_adherant"] ?>"><?= $_adherent["nom"] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-4 items-center">
            <label class="text-gray-500 font-bold pr-4" for="fm-nature">
              Nature </label>
            <div class="col-span-3">
              <input type="text" id="fm-nature" name="nature"
                class="w-11/12 p-2 text-gray-700 border-2 border-gray-200 rounded leading-tight appearance-none focus:outline-none focus:bg-white focus:border-blue-500">
            </div>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
        <button type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Ajouter</button>
        <button data-close-modal type="button"
          class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Annuler</button>
      </div>
    </div>
  </form>
</div>