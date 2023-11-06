<?php
    $servername = "localhost";
    $database = "vuejs_5";
    $username = "dougMoura";
    $password = "22_Moura_@";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    echo "Conexão bem sucedida: " . mysqli_close($conn);
?>