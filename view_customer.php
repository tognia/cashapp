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
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        CLIENT
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
              <?php
                $id = $_GET['id'];
				
				//echo $id;

                $select = $pdo->prepare("SELECT * FROM users WHERE user_id=$id");
                $select->execute();
                while($row = $select->fetch(PDO::FETCH_OBJ)){ ?>

                <div class="col-md-6">
                  <ul class="list-group">

                    <center><p class="list-group-item list-group-item-success">Details Client</p></center>
                    <li class="list-group-item"> <b>ID</b>     :<span class="label badge pull-right"><?php echo $row->user_id; ?></span></li>
                    <li class="list-group-item"><b>Nom Client</b>    :<span class="label label-info pull-right"><?php echo $row->firstname." ".$row->middlename." ".$row->lastname; ?></span></li>
                    <li class="list-group-item"><b>Adresse</b>        :<span class="label label-primary pull-right"><?php echo $row->address; ?></span></li>

                    <li class="list-group-item"><b>Email</b>  :<span class="label label-warning pull-right"> <?php echo $row->email; ?></span></li>
                    <li class="list-group-item"><b>Contact Tel.</b>  :<span class="label label-warning pull-right"> <?php echo $row->contact; ?></span></li>
                    <li class="list-group-item"><b>Nom Utilisateur</b>     :<span class="label label-warning pull-right"> <?php echo $row->username; ?></span></li>
                    <li class="list-group-item"><b>Type Client</b>  :<span class="label label-warning pull-right"> <?php echo $row->type;?></span></li>
                   
                    
                  </ul>
                </div>
                
              <?php
                }
              ?>
            </div>
            <div class="box-footer">
                <a href="customer.php" class="btn btn-warning">Retour</a>
            </div>

        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'inc/footer_all.php';
 ?>