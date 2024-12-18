<?php

$user = "postgres";
$pass = "9703";

$db = new PDO("pgsql:dbname=108-finals host=localhost", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);