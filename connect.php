<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  session_start();
  // Generate a random token
  $token = bin2hex(random_bytes(32));
  // Store the token in the session
  $_SESSION['id'] = $token;
  header('Location: /project-bd/');
}

?>

<!doctype html>
<html>

<head>
  <?php require_once "src/components/head.php" ?>
</head>

<body>
  <main class="my-10">
    <div class="container mx-auto px-5">
      <div class="bg-white p-4 shadow rounded">
        <h1 class="text-3xl font-semibold mb-4">Connexion</h1>
        <form action="" method="post">
          <div class="mb-4">
            <label for="email" class="block mb-2">Adresse email</label>
            <input type="text" name="email" id="email" value="admin" class="w-full border border-gray-300 rounded p-2"
              required>
          </div>
          <div class="mb-4">
            <label for="password" class="block mb-2">Mot de passe</label>
            <input type="password" name="password" id="password" value="admin"
              class="w-full border border-gray-300 rounded p-2" required>
          </div>
          <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white rounded p-2">Se connecter</button>
          </div>
        </form>
      </div>
    </div>
  </main>
</body>

</html>