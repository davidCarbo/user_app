<?php
require_once 'includes/connect.php';

$sql = "CREATE TABLE IF NOT EXISTS users(
        user_id int(255) auto_increment not null,
        name varchar(50),  
        surname varchar(255),
        bio text,
        email varchar(255),
        password varchar(255),
        rol varchar(20),
        image varchar(255),
        CONSTRAINT pk_users PRIMARY KEY(user_id)
        );";

$create_usuarios_table = mysqli_query($db, $sql);


$sql= "INSERT INTO users VALUE (NULL, 'Cristina', 'Sirvent', 'La persona más increible que he conocido', 
'cristina@gmail.com', '".sha1("password")."', '0', NULL)";

$insert_user = mysqli_query($db, $sql);

$sql= "INSERT INTO users VALUE (NULL, 'Goku', 'Carbó', 'perrito', 
'goku@gmail.com', '".sha1("password")."', '1', NULL)";

$insert_user = mysqli_query($db, $sql);


if($create_usuarios_table){
    echo "La tabla se ha creado con éxito <br/>";
} else {
    echo "Prueba otra vez";
}

if($insert_user){
    echo "Usuario añadido correctamente";
} else {
    echo "Usurio no añadido. Prueba otra vez";
}

?>


