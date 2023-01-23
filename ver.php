<?php include 'includes/redirect.php'; ?>
<?php include 'includes/header.php'; ?>

<?php

if (!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location:index.php");
}

$id = $_GET["id"];
$user_query = mysqli_query($db, "SELECT * FROM users WHERE user_id={$id}");
$user = mysqli_fetch_assoc($user_query);
if (!isset($user["user_id"]) || empty($user["user_id"])) {
    header("Location:index.php");
}

?>
<div>
    <?php if ($user["image"] != null) { ?>
        <img src="uploads/<?= $user["image"] ?>" width="120" />
    <?php } ?>

    <h3><?php echo $user["name"] . " " . $user["surname"] ?></h3>
    <p> Email: <?php echo $user["email"] ?> </p>
    <p> Biografía: <?php echo $user["bio"] ?> </p>
</div>
<!-- <a href="index.php" class="btn btn-success">Volver</a> -->



<?php include 'includes/footer.php'; ?>