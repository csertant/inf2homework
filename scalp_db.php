<?php

function connectDatabase(){
    $connection = mysqli_connect("localhost", "root", "")
        or die("Connection error: " . mysqli_error());
    mysqli_select_db($connection, "scalp");
    mysqli_query ($connection, "set character_set_results='utf8'");
    mysqli_query ($connection, "set character_set_client='utf8'");
    return $connection;
}

function disconnectDatabase($connection){
    mysqli_close($connection);
}

?>