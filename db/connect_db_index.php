<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=cashapp', 'peace', 'Eyezonkamer1&@');
    //echo 'Connection Successfull';
} catch (PDOException $error) {
    echo $error->getmessage();
}


$_SESSION['derniere_action'] = time();

//require_once("deconnect.php");


?>

<script type="text/javascript">
    //setTimeOut("window.location.href='../index.php';", 30000);
</script>