<?php include 'includes/redirect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php

function showError($errors, $field)
{
    if (isset($errors[$field]) && !empty($field)) {
        $alert = '<div class="alert alert-danger" style="margin-top:5px;">' . $errors[$field] . '</div>';
    } else {
        $alert = '';
    }

    return $alert;
}

function setValueField($data, $field, $textarea = false)
{
    if (isset($data) && (count($data) >= 1)) {
        if ($textarea != false) {
            echo $data[$field];
        } else {
            echo "value='{$data[$field]}'";
        }
    }
}

//Conseguir usuario

if (!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location:index.php");
}

$id = $_GET["id"];
$user_query = mysqli_query($db, "SELECT * FROM users WHERE user_id={$id}");
$user = mysqli_fetch_assoc($user_query);
if (!isset($user["user_id"]) || empty($user["user_id"])) {
    header("Location:index.php");
}

// Validar el formulario 

$errors = array();
if (isset($_POST["submit"])) {
    if (!empty($_POST["name"]) && strlen($_POST["name"]) <= 20 && !is_numeric($_POST["name"]) && !preg_match("/[0-9]/", $_POST["name"])) {
        $name_validate = true;
    } else {
        $errors["name"] = "El nombre no es válido";
        $name_validate = false;
    }
    if (!empty($_POST["surname"]) && !is_numeric($_POST["surname"]) && !preg_match("/[0-9]/", $_POST["surname"])) {
        $surname_validate = true;
    } else {
        $errors["surname"] = "Los apellidos no son válidos";
        $surname_validate = false;
    }
    if (!empty($_POST["bio"])) {
        $bio_validate = true;
    } else {
        $errors["bio"] = "La biografía no puede estar vacía";
        $bio_validate = false;
    }
    if (!empty($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_validate = true;
    } else {
        $errors["email"] = "Introduce un mail correcto";
        $email_validate = false;
    }
    /*
    if (!empty($_POST["password"]) && strlen($_POST["password"]) >= 6) {
        $password_validate = true;
    } else {
        $errors["password"] = "Introduce una contraseña de más de 6 caracteres";
        $password_validate = false;
    }*/
    if (isset($_POST["rol"]) && is_numeric($_POST["rol"])) {
        $rol_validate = true;
    } else {
        $errors["rol"] = "Selecciona un rol de usuario";
        $rol_validate = false;
    }

    $image = null;
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        if (!is_dir("uploads")) {
            $dir = mkdir("uploads", 0777, true);
        } else {
            $dir = true;
        }

        if ($dir) {
            $filename = time() . "-" . $_FILES["image"]["name"];
            $muf = move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $filename);

            $image = $filename;

            if ($muf) {
                $image_upload = true;
            } else {
                $image_upload = false;
                $errors["image"] = "La imagen no se ha subido correctamente.";
            }
        }
    }

    // Actualizar usuario en la BBDD

    if (count($errors) == 0) {
        $sql = "UPDATE users set name = '{$_POST["name"]}', "
            . "surname = '{$_POST["surname"]}', "
            . "bio = '{$_POST["bio"]}', "
            . "email = '{$_POST["email"]}', ";

        if (isset($_POST["password"]) && !empty($_POST["password"])) {
            $sql .= "password = '" . sha1($_POST['password']) . "', ";
        }

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $sql .= "image = '{$image}', ";
        }

        $sql .= "rol = '{$_POST["rol"]}' WHERE user_id='{$user["user_id"]}';";

        $update_user = mysqli_query($db, $sql);

        if ($update_user) {
            $user_query = mysqli_query($db, "SELECT * FROM users WHERE user_id={$id}");
            $user = mysqli_fetch_assoc($user_query);
        }
    } else {
        $update_user = false;
    }
}

?>


<h2>Editar usuario <?php echo $user["user_id"] . " - " . $user["name"] . " " . $user["surname"]; ?></h2>


<?php if (isset($_POST["submit"]) && count($errors) == 0 && $update_user != false) { ?>
    <div class="alert alert-success">El usuario se ha actualizado correctamente. </div>
<?php } elseif (isset($_POST["submit"])) { ?>
    <div class="alert alert-danger">El usuario NO se ha actualizado. </div>
<?php } ?>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="name">Nombre:
        <input type="text" name="name" class="form-control" <?php setValueField($user, "name"); ?> />
        <?php echo showError($errors, "name") ?>
    </label>
    </br>

    <label for="surname">Apellidos:
        <input type="text" name="surname" class="form-control" <?php setValueField($user, "surname"); ?> />
        <?php echo showError($errors, "surname") ?>
    </label>
    </br>

    <label for="bio">Biografía:
        <textarea name="bio" class="form-control"><?php setValueField($user, "bio", true); ?></textarea>
        <?php echo showError($errors, "bio") ?>
    </label>
    </br>

    <label for="email">Correo:
        <input type="email" name="email" class="form-control" <?php setValueField($user, "email"); ?> />
        <?php echo showError($errors, "email") ?>
    </label>
    </br>

    <label for="image">
        <p>Imagen:</p>
        <?php if ($user["image"] != null) { ?>
            <img src="uploads/<?= $user["image"] ?>" width="120" />
        <?php } ?>
        <input type="file" name="image" class="form-control" />
    </label>
    </br>

    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control" />
        <?php echo showError($errors, "password") ?>
    </label>
    </br>

    <label for="rol">Rol:
        <select name="rol" class="form-control">
            <option value="0" <?php if ($user["rol"] == 0) {
                                    echo "selected='selected'";
                                } ?>>Normal</option>
            <option value="1" <?php if ($user["rol"] == 1) {
                                    echo "selected='selected'";
                                } ?>>Administrador</option>
        </select>
        <?php echo showError($errors, "rol") ?>
    </label>
    </br>

    <input type="submit" value="enviar" name="submit" class="btn btn-success" style="margin-top: 5px;">
</form>
<?php require_once 'includes/footer.php'; ?>