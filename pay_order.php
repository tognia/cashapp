<?php
	//require_once('auth.php');
	include_once'db/connect_db.php';
    session_start();
    if($_SESSION['user_name']==""){
        header('location:index.php');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once'inc/header_all.php';
        }else{
            include_once'inc/header_all_operator.php';
        }
    }
	
	
	error_reporting(0);
	
	

	if(isset($_POST['submit'])){

    $firstname=$_POST['firstname'];
    $middlename=$_POST['middlename'];
    $lastname=$_POST['lastname'];
    $address=$_POST['address'];
    $email=$_POST['email'];
    $contact=$_POST['contact'];
    $username=$_POST['username'];
    $password=sha1($_POST['password']);
    $type=$_POST['type'];

    //$pass1=sha1($password);
    //$salt="a1Bz20ydqelm8m1wql";
    //$pass1=$salt.$pass1;
       //check if the email already exist
        if(isset($_POST['username'])){
            $select = $pdo->prepare("SELECT username FROM users WHERE username='$username'");
            $select->execute();

            if($select->rowCount() > 0 ){
                echo'<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Client deja enregistre sous ce nom", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
            } else {
                //insert query here
                $insert = $pdo->prepare("INSERT INTO users(firstname,middlename,lastname,address,email,contact,username,password,type) VALUES(:firstname,:middlename,:lastname,:address,:email,:contact,:username,:password,:type)");

                //binding the values parameter with input from user
                $insert->bindParam(':firstname',$firstname);
                $insert->bindParam(':middlename',$middlename);
                $insert->bindParam(':lastname',$lastname);
                $insert->bindParam(':address',$address);
                $insert->bindParam(':email',$email);
                $insert->bindParam(':contact',$contact);
				$insert->bindParam(':username',$username);
				$insert->bindParam(':password',$password);
				$insert->bindParam(':type',$type);
				
                //if execution $insert
                if($insert->execute()){
                    echo'<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Enregistrement reussi", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
                }
            }
        }
    }
	
	

	
?>
 
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('a[rel*=facebox]').facebox({
      loadingImage : 'src/loading.gif',
      closeImage   : 'src/closelabel.png'
    })
  })
</script>
</head>
<?php
function createRandomPassword() {
	$chars = "003232303232023232023456789";
	srand((double)microtime()*1000000);
	$i = 0;
	$pass = '' ;
	while ($i <= 7) {

		$num = rand() % 33;

		$tmp = substr($chars, $num, 1);

		$pass = $pass . $tmp;

		$i++;

	}
	return $pass;
}
$finalcode='RS-'.createRandomPassword();
?>



 <script language="javascript" type="text/javascript">
/* Visit http://www.yaldex.com/ for full source code
and get more free JavaScript, CSS and DHTML scripts! */
<!-- Begin
var timerID = null;
var timerRunning = false;
function stopclock (){
if(timerRunning)
clearTimeout(timerID);
timerRunning = false;
}
function showtime () {
var now = new Date();
var hours = now.getHours();
var minutes = now.getMinutes();
var seconds = now.getSeconds()
var timeValue = "" + ((hours >12) ? hours -12 :hours)
if (timeValue == "0") timeValue = 12;
timeValue += ((minutes < 10) ? ":0" : ":") + minutes
timeValue += ((seconds < 10) ? ":0" : ":") + seconds
timeValue += (hours >= 12) ? " P.M." : " A.M."
document.clock.face.value = timeValue;
timerID = setTimeout("showtime()",1000);
timerRunning = true;
}
function startclock() {
stopclock();
showtime();
}
window.onload=startclock;
// End -->
</SCRIPT>
<body>
<?php //include('navfixed.php');

        include("include/connect_db.php");


        /********************************* PAY ORDER *******************************************/


    if (isset($_POST["pay"])) {  
                

            $id = $_POST['id'];

                  $moyen_paiement = $_POST['moyen_paiement'];
                  $date_paiement = date("Y-m-d",strtotime($_POST['date_paiement']));
                  $time_paiement = date("H:i", strtotime($_POST['time_paiement']));
                  $infos_paiement = $_POST['infos_paiement'];
                 
                  $status = "payed";

            $payed_query ="UPDATE tbl_invoice_client  SET Status = 'paid', date_paiement = '$date_paiement', time_paiement = '$time_paiement', infos_paiement = '$infos_paiement' WHERE invoice_id = '$id' ";

            $payed_query1 ="UPDATE tbl_invoice_detail_client  SET Status = 'paid' WHERE invoice_id = '$id' ";

            
    
    if(mysqli_query($conn, $payed_query) && mysqli_query($conn, $payed_query1)){
        echo'<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Commande Payee avec success", "info", {
            button: "Continue",
                });
            });
            </script>';

     
    }

    else { echo " ECHEC VALIDATION PAIEMENT";

        }


       


    } 

    header('refresh:2;orderclient.php');



    /********************************* PAY ORDER *******************************************/


?>



<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <form action="" method="POST">
            <!-- Registration Form -->
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">CONFIRMATION PAIEMENT FACTURE</h3> 
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                        
                        <div class="box-body">

                                <?php


                                if (isset($_GET["id"])) {
                                    
                                  $id = $_GET["id"];

                            $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE invoice_id = '$id' AND status <> 'deleted'  ORDER BY invoice_id DESC");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                
                                <div class="form-group">
                                    <label for="Numero">Numero Facture</label>
                                    <input type="text" class="form-control" id="firstname" name="id" value="<?php echo $row->invoice_id; ?>" readonly="">
                                </div>
                                <div class="form-group">
                                    <label for="Total">Total Facture</label>
                                    <input type="text" class="form-control" id="total" name="total" value="<?php echo number_format($row->total,0,null,' ').' FCFA'; ?>" readonly="">
                                </div>
                                <div class="form-group">
                                    <label for="date">Date Facture</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo date('d-m-Y',strtotime($row->order_date)).' '.$row->time_order; ?>" readonly="">
                                </div>
                                <div class="form-group">
                                    <label for="id_client">Nom Utilisateur</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $row->id_client; ?>" readonly="">
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Nom Client </label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $row->name_client; ?>"readonly="">
                                </div>

                                <div class="dropdown">
                                    <label>Moyen Paiement</label>
                                    <select class="form-control" name="moyen_paiement" required>
                                        <option value="Especes">Especes</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Orange Money">Orange Money</option>
                                        <option value="MTN Money">MTN Money</option>
                                        <option value="Visa">Visa</option>
                                    </select>

                                    <div class="input-group">
                                      <span class="input-group-text">Infos Paiement</span>
                                      <textarea class="form-control" name="infos_paiement" aria-label="Infos Paiement"><?php echo $row->infos_paiement; ?></textarea>
                                    </div>

                                    <input type="hidden" name="date_paiement" value="<?php echo date("d-m-Y");?>">
                                    <input type="hidden" name="time_paiement" value="<?php echo date('H:i') ?>">

                                                                
                                <?php

                                        }

                                    }

                                ?>
                                
                               
                        </div><!-- /.box-body -->

                        <div class="box-footer">

                            <a href="orderclient.php" class="btn btn-warning">Retour</a>

                            <?php
                                    if (!isset($_POST["pay"])) {
                            ?>                            
                            <button type="submit" class="btn btn-primary" name="pay">Enregistrer</button>

                            <?php
                                
                                }
                            ?>  
                        </div>

                    </form>
                </div>
            </div>




<div class="container-fluid">
      <div class="row-fluid">
	<div class="span2">
          
        </div><!--/span-->
	<div class="span10">
		
<div style="margin-top: -19px; margin-bottom: 21px;">


<div class="box-body">
                <div style="overflow-x:auto;">
                    

</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<script src="js/jquery.js"></script>
  <script type="text/javascript">
$(function() {


$(".delbutton").click(function(){

//Save the link in a variable called element
var element = $(this);

//Find the id of the link that was clicked
var del_id = element.attr("id");

//Built a url to send
var info = 'id=' + del_id;
 if(confirm("Are you sure want to delete? There is NO undo!"))
		  {

 $.ajax({
   type: "GET",
   url: "deletesupplier.php",
   data: info,
   success: function(){
   
   }
 });
         $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
		.animate({ opacity: "hide" }, "slow");

 }

return false;

});

});
</script>

<script>
  $(document).ready( function () {
      $('#myProduct').DataTable();
  } );
  </script>

</body>
<?php include('inc/footer_all.php');?>

</html>