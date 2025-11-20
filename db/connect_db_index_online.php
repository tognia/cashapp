<?php

try{
    $pdo = new PDO('mysql:host=91.216.107.162;dbname=nuvib1449089_12h1rf','nuvib1449089','dpwhcxicbs');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}

$_SESSION['derniere_action'] = time();


?>