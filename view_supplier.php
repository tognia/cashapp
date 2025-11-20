<?php
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
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Fournisseur
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
              <?php
                $id = $_GET['id'];
				
				//echo $id;

                $select = $pdo->prepare("SELECT * FROM supliers WHERE suplier_id=$id");
                $select->execute();
                while($row = $select->fetch(PDO::FETCH_OBJ)){ ?>

                <div class="col-md-6">
                  <ul class="list-group">

                    <center><p class="list-group-item list-group-item-success">Details Fournisseur</p></center>
                    <li class="list-group-item"> <b>Code Produit</b>     :<span class="label badge pull-right"><?php echo $row->suplier_id; ?></span></li>
                    <li class="list-group-item"><b>Nom Fournisseur</b>    :<span class="label label-info pull-right"><?php echo $row->suplier_name; ?></span></li>
                    <li class="list-group-item"><b>Adresse</b>        :<span class="label label-primary pull-right"><?php echo $row->suplier_address; ?></span></li>
                    <li class="list-group-item"><b>Contact</b>  :<span class="label label-warning pull-right"> <?php echo $row->suplier_contact; ?></span></li>
                    <li class="list-group-item"><b>Personne A Contacter</b>     :<span class="label label-warning pull-right"> <?php echo $row->contact_person; ?></span></li>
                    <li class="list-group-item"><b>Note</b>           :<span class="label label-success pull-right"> <?php echo $row->note; ?></span></li>
                    
                  </ul>
                </div>
                
              <?php
                }
              ?>
            </div>
            <div class="box-footer">
                <a href="supplier.php" class="btn btn-warning">Retour</a>
            </div>

        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'inc/footer_all.php';
 ?>