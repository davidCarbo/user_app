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

function setValueField($errors, $field, $textarea = false)
{
    if (isset($errors) && (count($errors) >= 1) && isset($_POST[$field])) {
        if ($textarea != false) {
            echo $_POST[$field];
        } else {
            echo "value='{$_POST[$field]}'";
        }
    }
}

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
    if (!empty($_POST["password"]) && strlen($_POST["password"]) >= 6) {
        $password_validate = true;
    } else {
        $errors["password"] = "Introduce una contraseña de más de 6 caracteres";
        $password_validate = false;
    }
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

    // Insertar usuario en la BBDD

    if (count($errors) == 0) {
        $sql = "INSERT INTO users VALUE (NULL, '{$_POST["name"]}', '{$_POST["surname"]}', 
                '{$_POST["bio"]}', 
                '{$_POST["email"]}', '" . sha1($_POST['password']) . "', '{$_POST["rol"]}', '{$image}');";
        $insert_user = mysqli_query($db, $sql);
    } else {
        $insert_user = false;
    }
}
?>


<h2>Crear usuarios</h2>

<?php if (isset($_POST["submit"]) && count($errors) == 0 && $insert_user != false) { ?>
    <div class="alert alert-success">El usuario se ha creado correctamente </div>
<?php } ?>


<form action="" method="POST" enctype="multipart/form-data">
    <label for="name">Nombre:
        <input type="text" name="name" class="form-control" <?php setValueField($errors, "name"); ?> />
        <?php echo showError($errors, "name") ?>
    </label>
    </br>

    <label for="surname">Apellidos:
        <input type="text" name="surname" class="form-control" <?php setValueField($errors, "surname"); ?> />
        <?php echo showError($errors, "surname") ?>
    </label>
    </br>

    <label for="bio">Biografía:
        <textarea name="bio" class="form-control"><?php setValueField($errors, "bio", true); ?></textarea>
        <?php echo showError($errors, "bio") ?>
    </label>
    </br>

    <label for="email">Correo:
        <input type="email" name="email" class="form-control" <?php setValueField($errors, "email"); ?> />
        <?php echo showError($errors, "email") ?>
    </label>
    </br>

    <label for="image">Imagen:
        <input type="file" name="image" class="form-control" <?php setValueField($errors, "image"); ?> />
        <?php echo showError($errors, "image") ?>
    </label>
    </br>

    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control" />
        <?php echo showError($errors, "password") ?>
    </label>
    </br>

    <label for="rol">
        Rol:
        <select name="rol" class="form-control">
            <option value="0">Normal</option>
            <option value="1">Administrador</option>
        </select>
        <?php echo showError($errors, "rol") ?>
    </label>
    </br>

    <input type="submit" value="enviar" name="submit" class="btn btn-success" style="margin-top: 5px;" />
</form>
<?php require_once 'includes/footer.php'; ?>