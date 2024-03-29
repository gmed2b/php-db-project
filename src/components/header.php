<header class="bg-zinc-100 shadow-xl p-4">
  <div class="container mx-auto flex justify-between items-center">
    <a href="/project-bd/">
      <h1 class="text-xl font-semibold">ASCG Dashboard</h1>
    </a>
    <div class="ml-auto flex items-center">
      <a href="/project-bd/adherents/" class="text-zinc-500 hover:text-zinc-600 mr-4">Adhérants</a>
      <a href="/project-bd/liens_parente/" class="text-zinc-500 hover:text-zinc-600 mr-4">Liens</a>
      <a href="/project-bd/benevoles/" class="text-zinc-500 hover:text-zinc-600 mr-4">Bénévoles</a>
      <a href="/project-bd/sections/" class="text-zinc-500 hover:text-zinc-600 mr-4">Sections</a>
      <a href="/project-bd/activites/" class="text-zinc-500 hover:text-zinc-600 mr-4">Activités</a>
      <?php if (isset($_SESSION['id'])): ?>
      <a href="/project-bd/deconnect.php" class="p-1.5 bg-red-400 text-white">Déconnexion</a>
      <?php endif; ?>
    </div>
  </div>
</header>