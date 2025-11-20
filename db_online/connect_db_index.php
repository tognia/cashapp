<?php

try{
    $pdo = new PDO('mysql:host=91.216.107.185;dbname=fyael2172471','fyael2172471','Eyezonkamer1@');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}

$_SESSION['derniere_action'] = time();


?>