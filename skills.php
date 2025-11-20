<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['user_name']==""){
        header('location:index.php');
    }else{
        if($_SESSION['role']=="Admin"||$_SESSION['role']=="Responsable"){
          include_once'inc/header_all.php';
        }else{
            include_once'inc/header_all_operator.php';
        }
    }

    error_reporting(0);

    $id = $_GET['id'];

    $today = date("j, n, Y");
    $today = date("Y-m-d");
    $magasin = $_SESSION['magasin'];

    $delete_query = "DELETE tbl_invoice , tbl_invoice_detail FROM tbl_invoice INNER JOIN tbl_invoice_detail ON tbl_invoice.invoice_id =
    tbl_invoice_detail.invoice_id WHERE tbl_invoice.invoice_id=$id";
    $delete = $pdo->prepare($delete_query);
    if($delete->execute()){
        echo'<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "La transaction supprimee", "info", {
            button: "Continue",
                });
            });
            </script>';
    }

    echo "BLABLA BLABLA BLABLA BLABLA BLABLA";
?>
<?php 
 
 if(isset($_POST['submit'])){ 
     $skill = $_POST['skill_input']; 
     echo 'Selected Skill: '.$skill; 
 } 
  
 ?>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- jQuery UI library -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<content>

<form method="post" action="submit.php">
    <!-- Autocomplete input field -->
    <div class="form-group">
        <label>Programming Language:</label>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" id="skill_input" name="skill_input" placeholder="Start typing...">
    </div>

    <!-- Submit button -->
    <input type="submit" name="submit" value="Submit">
</form>


<section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des transactions</h3>
                AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            </div>
        </div>
</section>
</content>


<script>
$(function() {
    $("#skill_input").autocomplete({
        source: "fetchData.php",
        select: function( event, ui ) {
            event.preventDefault();
            $("#skill_input").val(ui.item.id);
        }
    });
});
</script>

 <?php
    include_once'inc/footer_all.php';
 ?>