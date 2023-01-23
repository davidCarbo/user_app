<?php
include 'includes/redirect.php';
include 'includes/header.php';

$users = mysqli_query($db, "SELECT * FROM users");
$num_total_users = mysqli_num_rows($users);

if ($num_total_users > 0) {

    $rows_per_page = 9;
    $page = false;

    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }

    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $rows_per_page;
    }

    $total_pages = ceil($num_total_users / $rows_per_page);

    $sql = "SELECT * FROM users ORDER BY user_id DESC LIMIT {$start}, {$rows_per_page};";
    $users = mysqli_query($db, $sql);
} else {
    echo "No hay usuarios";
}

?>


<table class="table">
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Ver/Editar</th>
    </tr>

    <?php while ($user = mysqli_fetch_assoc($users)) { ?>

        <tr>
            <td><?= $user["name"] ?> </td>
            <td><?= $user["surname"] ?> </td>
            <td><?= $user["email"] ?> </td>
            <td>
                <a href="ver.php?id=<?= $user["user_id"] ?>" class="btn btn-success">Ver</a>
                <?php
                if (isset($_SESSION["logged"]) && $_SESSION["logged"]["rol"] == 1) { ?>
                    <a href="editar.php?id=<?= $user["user_id"] ?>" class="btn btn-warning">Editar</a>
                    <a href="borrar.php?id=<?= $user["user_id"] ?>" class="btn btn-danger">Borrar</a>
                <?php } ?>
            </td>
        </tr>

    <?php } ?>
</table>

<?php if ($num_total_users >= 1) { ?>
    <ul class="pagination">
        <li class="page-item"> <a class="page-link" href="?page=<?= $page - 1 ?>">
                < </a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

            <?php if ($page == $i) { ?>
                <li class="page-item disabled"> <a class="page-link" href="#"><?= $i ?></a> </li>
            <?php } else { ?>
                <li class="page-item"> <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a> </li>
            <?php } ?>

        <?php } ?>
        <li class="page-item"> <a class="page-link" href="?page=<?php if (($page + 1) <= $total_pages) {
                                                                    echo $page + 1;
                                                                } else {
                                                                    echo $total_pages;
                                                                } ?>"> > </a></li>
    </ul>
<?php } ?>

<?php
include 'includes/footer.php';
?>