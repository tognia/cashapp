<?php

try{
    $pdo = new PDO('mysql:host=91.216.107.185;dbname=fyael2172471','fyael2172471','Eyezonkamer1@');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}

session_start();

if( isset($_SESSION['derniere_action']) and ($_SESSION['derniere_action'] >= time() - 3600) ) {

/* time() + 300 secondes = heure actuelle + 5 min */

/* donc dans ce cas, la dernière action date de moins de 5 minutes */

 //echo $_SESSION['derniere_action']."     ";

$_SESSION['derniere_action'] = time();

//echo time();


//$_SESSION['derniere_action'] = time(); // mise à jour de la variable

} else 


{

/* soit pas encore de session ouverte => pas identifier */

/* soit derniere action vielle de plus de 5 minutes => deconexion */

/* DONC renvoi vers ta page "hors connexion" */
//session_start();

    session_destroy(); 

    unset($_SESSION['username']);
    $_SESSION = [];

    //header('refresh:2;index.php');
    header('Location: index.php'); 



}



?>

