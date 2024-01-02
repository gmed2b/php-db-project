<?php

require_once "../src/init.php";

$sections = $db->query("SELECT code_section, nom FROM sections;")->fetchAll();

?>

<div id="inscription-modal" class="modal rounded-lg">
  <form action="inscription.php" method="post">
    <input type="hidden" name="nro_adherant" value="<?= $CURR_ADHERENT["nro_adherant"] ?>">

    <div class="relative bg-white rounded-lg shadow ">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
        <h3 class="text-xl font-semibold text-gray-900 ">
          Inscription à une activité
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
        <div class="grid grid-cols-4 items-center">
          <label class="text-gray-500 font-bold pr-4" for="section-select">
            Section</label>
          <div class="col-span-3">
            <select id="section-select" name="section"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              <option selected>Choisir une section</option>
              <?php foreach ($sections as $section) : ?>
              <option value="<?= $section["code_section"] ?>"><?= $section["nom"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div id="activite-section" class="hidden grid-cols-4 items-center">
          <label class="text-gray-500 font-bold pr-4" for="activite-select">
            Activité</label>
          <div class="col-span-3">
            <select name="activite" id="activite-select"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              <option selected>Choisir une activité</option>
            </select>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b ">
        <button type="submit"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Inscrire</button>
        <button data-close-modal type="button"
          class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Annuler</button>
      </div>
    </div>
  </form>
</div>


<script>
const chargerActivites = async (section) => {
  try {
    const formData = new FormData();
    formData.append('section', section);

    const response = await fetch('get_activites.php', {
      method: 'POST',
      body: formData
    });

    if (!response.ok) {
      throw new Error('Problème lors de la récupération des activités.');
    }

    const activites = await response.json();
    const activiteSelect = document.getElementById('activite-select');
    activiteSelect.innerHTML = '<option selected>Choisir une activité</option>';

    activites.forEach((activite) => {
      const option = document.createElement('option');
      option.value = activite.nro_activite;
      option.textContent = `${activite.libelle} - ${
        activite.places_disponibles ? 'Disponibles' : 'Complet'
      }`;
      option.disabled = !activite.places_disponibles;
      activiteSelect.appendChild(option);
    });
  } catch (error) {
    console.error('Erreur:', error);
  }
};

const toggleActiviteSection = (shouldShow) => {
  const activiteSection = document.getElementById('activite-section');
  activiteSection.classList.toggle('grid', shouldShow);
  activiteSection.classList.toggle('hidden', !shouldShow);
};

document.getElementById('section-select').addEventListener('change', (event) => {
  const selectedSection = event.target.value;
  const activiteSelect = document.getElementById('activite-select');

  if (selectedSection !== 'Choisir une section') {
    chargerActivites(selectedSection);
    toggleActiviteSection(true);
  } else {
    activiteSelect.innerHTML = '<option selected>Choisir une activité</option>';
    toggleActiviteSection(false);
  }
});
</script>