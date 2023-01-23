<?php
// Inicio sesiones
session_start();

if (isset($_SESSION["logged"])) {
  header("Location: index.php");
}
?>

<?php require_once 'includes/header.php'; ?>
<h2>Identificate</h2>

<div style="max-width: 20rem;">
  <?php if (isset($_SESSION["error_login"])) { ?>
    <div class="alert alert-danger"> <?= $_SESSION["error_login"] ?></div>
  <?php } ?>

  <form action="login-user.php" method="post">
    Email: <input type="email" name="email" class="form-control" />
    Contraseña: <input type="password" name="password" class="form-control" />
    <br />
    <input type="submit" class="btn btn-success" name="submit" value="Entrar" />
  </form>
</div>
<?php require_once 'includes/footer.php' ?>;