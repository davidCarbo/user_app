<?php
require_once 'connect.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Web con php</title>
    <link type="text/css" rel="stylesheet" href="assets/components/bootstrap/css/bootstrap.min.css" />
    <!-- <link type="text/css" rel="stylesheet" href="assets/components/bootstrap/css/bootstrap-theme.min.css" /> -->
    <script type="text/javascript" src="assets/components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/components/jquery/jquery-3.6.3.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>Web con PHP</h1>
        <hr />
        <?php if (isset($_SESSION["logged"])) { ?>
            <div class="navbar navbar-light bg-light">
                <div>
                    <a href="index.php" class="btn btn-success">Home</a>
                    <a href="crear.php" class="btn btn-primary">Crear nuevo usuario</a>
                </div>
                <div>
                    <?php echo "Bienvenido " . $_SESSION["logged"]["name"] . " " . $_SESSION["logged"]["surname"]; ?>
                    <div><a href="logout.php">Cerrar sesión</a></div>
                </div>
            </div>
            <hr />
        <?php } ?>