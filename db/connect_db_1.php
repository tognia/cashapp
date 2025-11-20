<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=bevilec','root','');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}


?>