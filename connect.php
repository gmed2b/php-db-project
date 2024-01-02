<?php

session_start();

// Generate a random token
$token = bin2hex(random_bytes(32));

// Store the token in the session
$_SESSION['id'] = $token;

header('Location: /project-bd/');