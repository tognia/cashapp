
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->

    <section class="content-header">
      <h1>
        Transactions
      </h1>
      <hr>
    </section>
   
   <?php $today = date("Y-m-d"); ?>

    <section class="content container-fluid">
        <div class="box box-success">
          <form action="" method="POST" autocomplete="off">
            <div class="box-header with-border">
                <h3 class="box-title">Date Debut : <?php 

                          if(isset($_POST['date_filter'])){
                               echo $_POST['date_1'];
                             } 
                               ?>
                </h3>
                <h3 class="box-title">Date Fin : <?php 

                          if(isset($_POST['date_filter'])){
                               echo $_POST['date_2'];
                             } 

                               ?>
                </h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1" data-date-format="yyyy-mm-dd"
                       value="<?php 

                          if(isset($_POST['date_filter'])){
                               echo $_POST['date_1'];
                             } else{
                              echo $today;
                             }
                             ?>
                             ">
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2" data-date-format="yyyy-mm-dd" value="<?php 

                          if(isset($_POST['date_filter'])){
                               echo $_POST['date_2'];
                             } else{
                              echo $today;
                             }
                             ?>">
                    </div>
                  </div>
                </div>

                <div>

                <?php if($_SESSION['role']=="Admin") {  ?>
                  
                <label for="">Magasin</label>
                            <select class="form-control" name="shop" required>
                                <option value="all">all</option>
                                <?php
                                $select1 = $pdo->prepare("SELECT * FROM agence");
                                $select1->execute();
                                while($row = $select1->fetch(PDO::FETCH_ASSOC)){
                                    extract($row)
                                ?>
                                    <option value="<?php echo $row['code_agence']; ?>"><?php echo $row['code_agence']." ".$row['libelle_agence']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                <?php }  ?>
                <!--<input type="submit" name="select_shop" value="SELECTIONNER UN MAGASIN">-->
                <br><br>

                </div>

                <div class="col-md-2">
                  <input type="submit" name="date_filter" value="Afficher" class="btn btn-success btn-sm">
                </div>
                <br>
              </div>
                  <?php


              //////////////////////////////////////////////------- 

                $mgs = $_SESSION['magasin'];

                if(isset($_POST['date_filter'])){

                $leshop = $_POST['shop'];

                if($_SESSION['role']=="Admin" && $leshop=="all"){
                    $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_commandes_magasin
                    WHERE  order_date BETWEEN :fromdate AND :todate");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                }


                elseif($_SESSION['role']=="Admin" && $leshop!="all"){
                    $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_commandes_magasin
                    WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$leshop' ) AND order_date BETWEEN :fromdate AND :todate");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                }

                else{

                    
                    $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_commandes_magasin
                    WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$mgs' ) AND order_date BETWEEN :fromdate AND :todate");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                }

              }

              //////////////////////////////////////////////------- END


              elseif ($_SESSION['role']=="Admin") {
                $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_commandes_magasin ");
                    
                }

              else {

                    $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_commandes_magasin
                    WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$mgs' )");


              }



                    $select->execute();

                    $row = $select->fetch(PDO::FETCH_OBJ);

                    $total = $row->total;

                    $invoice = $row->invoice;


                  ?>

              <div class="row">
                <div class="col-md-offset-2 col-md-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL COMMANDES SUR LA PERIODE </span>
                      <span class="info-box-number"><?php echo $invoice; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-offset-1 col-md-5 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">VALEUR TOTALE DES COMMANDES</span>
                      <span class="info-box-number"> <?php echo number_format($total,0)." FCFA" ; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->


                <!-- /.col -->
              </div>

              <!--- Transaction Table -->
    

              <!-- Transaction Graphic -->
              <?php
                  $select = $pdo->prepare("SELECT order_date, sum(total) as price FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY order_date");
                  $select->bindParam(':fromdate', $_POST['date_1']);
                  $select->bindParam(':todate', $_POST['date_2']);
                  $select->execute();
                  $total=[];
                  $date=[];
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      $total[]=$price;
                      $date[]=$order_date;

                  }
                  // echo json_encode($total);
              ?>
              <div class="chart">
                  <canvas id="myChart" style="height:10px;">

                  </canvas>
              </div>

              <?php
                  $select = $pdo->prepare("SELECT product_name, sum(qty) as q FROM tbl_invoice_detail WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY product_id");
                  $select->bindParam(':fromdate', $_POST['date_1']);
                  $select->bindParam(':todate', $_POST['date_2']);
                  $select->execute();
                  $pname=[];
                  $qty=[];
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      $pname[]=$product_name;
                      $qty[]=$q;

                  }
                  // echo json_encode($total);
              ?>
              <div class="chart">
                  <canvas id="myBestSellItem" style="height:20px;">
                  </canvas>
              </div>

          </div>

          </form>
       