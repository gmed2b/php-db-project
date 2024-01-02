<?php

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /project-bd/?error=not_logged_in');
    exit();
}