<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once "config/config.php";
require_once "config/loadenv.php";

require_once "database.php";