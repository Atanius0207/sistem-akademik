<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sistem_akademik";

    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = @mysqli_connect($servername,$username,$password,$dbname);

    if (!$conn) {
        $error_message = mysqli_connect_error();
        die('<strong>Gagal terhubung dengan database: </strong>
            <strong>' . $error_message . '</strong>');
    }
?>