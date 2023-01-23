<?php

// Conexión a la BBDD
$db = new mysqli("localhost", "root", "", "curso_php");
mysqli_query($db, "SET NAMES 'UTF8'");
