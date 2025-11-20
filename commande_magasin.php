<?php
include_once 'db/connect_db.php';
if ($_SESSION['user_name'] == "") {
  include_once 'inc/404.php';
} else {
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}

//$m = $_SESSION['magasin'];


error_reporting(0);
date_default_timezone_set('Africa/Douala');
$shop = $_SESSION['magasin'];

function fill_product($pdo)
{
  $output = '';

  $s = $_SESSION['magasin'];
  //"bev_oyomabang" ORDER BY product_name
  $req = "SELECT * FROM tbl_shop_item WHERE shop_code = ?";
  $select = $pdo->prepare($req);
  $select->execute([$s]);
  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row['product_id'] . '">' . $row["product_name"] . '_' . $row["product_code"] . '</option>';
    $output .= '<option value="' . $row['product_id'] . '">' . $row["product_code"] . '</option>';
  }

  return $output;
}


function fill_product1($pdo)
{
  $output = '';

  $select = $pdo->prepare("SELECT * FROM tbl_product ORDER BY product_name");
  $select->execute();
  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row['product_id'] . '">' . $row["product_name"] . '</option>';
  }

  return $output;
}

function fill_client($pdo)
{
  $output = '';

  $select = $pdo->prepare("SELECT * FROM users ORDER BY username");
  $select->execute();
  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row['username'] . '">' . $row["firstname"] . ' ' . $row["middlename"] . ' ' . $row["lastname"] . '</option>';
  }

  return $output;
}


$_SESSION['tab_alert'] = array();
$_SESSION['tab_alert']['id'] = array();
$_SESSION['tab_alert']['code'] = array();
$_SESSION['tab_alert']['name'] = array();
$_SESSION['tab_alert']['stock'] = array();
$_SESSION['tab_alert']['stock_min'] = array();

$j = 0;
$_SESSION['count_alert'] = 0;


if (isset($_POST['save_order'])) {
  $cashier_name = $_POST['cashier_name'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i", strtotime($_POST['timeorder']));
  $total = $_POST['thetotal'];
  


  $arr_product_id =  $_POST['productid'];
  $arr_product_code = $_POST['productcode'];
  $arr_product_name = $_POST['productname'];
  $arr_product_stock = $_POST['productstock'];
  $arr_product_stockmin = $_POST['minstock'];
  $arr_product_qty = $_POST['quantity'];
  $arr_product_satuan = $_POST['productsatuan'];
  $arr_product_price = $_POST['productprice'];
  $arr_product_total =  $_POST['producttotal'];

  if ($arr_product_code == "") {
    echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Veuillez remplir le formulaire de Commande", "warning", {
              button: "Continue",
                  });
              });
              </script>';
  } else {


    $insert = $pdo->prepare("INSERT INTO  tbl_commandes_magasin(cashier_name, shop, order_date, time_order, total)
        values(:cashiername, :shop, :orderdate, :timeorder, :total)");

    $insert->bindParam(':cashiername', $cashier_name);
    $insert->bindParam(':shop', $shop);
    $insert->bindParam(':orderdate',  $order_date);
    $insert->bindParam(':timeorder',  $order_time);
    $insert->bindParam(':total', $total);

    $insert->execute();


    $invoice_id = $pdo->lastInsertId();
    if ($invoice_id != null) {
      for ($i = 0; $i < count($arr_product_id); $i++) {

        $rem_qty = $arr_product_stock[$i] - $arr_product_qty[$i];
        $diff = $arr_product_price[$i] - $arr_product_min[$i];
        $reste = $rem_qty - $arr_product_stockmin[$i];

        $_SESSION['reste'] = $reste;

        if ($rem_qty < 0) {
          echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Stock Insuffisant !!!!!", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } 
        
        $insert = $pdo->prepare("INSERT INTO tbl_commandes_magasin_details(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, shop)
            values(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate, :shop)");

        $insert->bindParam(':invid',  $invoice_id);
        $insert->bindParam(':productid',   $arr_product_id[$i]);
        $insert->bindParam(':productcode',   $arr_product_code[$i]);
        $insert->bindParam(':productname', $arr_product_name[$i]);
        $insert->bindParam(':qty', $arr_product_qty[$i]);
        $insert->bindParam(':productsatuan', $arr_product_satuan[$i]);
        $insert->bindParam(':price',  $arr_product_price[$i]);
        $insert->bindParam(':total',   $arr_product_total[$i]);
        $insert->bindParam(':orderdate',  $order_date);
        $insert->bindParam(':shop',  $shop);
        $insert->execute();


        if ($reste <= 0) {


          //$_SESSION['tab_alert']=array();
          //$_SESSION['tab_alert']['id'][$j] = $arr_product_id[$i];
          array_push($_SESSION['tab_alert']['id'], $arr_product_id[$i]);
          //$_SESSION['tab_alert']['code'][$j] = $arr_product_code[$i];
          array_push($_SESSION['tab_alert']['code'], $arr_product_code[$i]);
          //$_SESSION['tab_alert']['name'][$j] = $arr_product_name[$i];
          array_push($_SESSION['tab_alert']['name'], $arr_product_name[$i]);
          //$_SESSION['tab_alert']['stock'][$j] = $arr_product_stock[$i];
          array_push($_SESSION['tab_alert']['stock'], $rem_qty);
          //$_SESSION['tab_alert']['stock_min'][$j] = $arr_product_stock_min[$i];
          array_push($_SESSION['tab_alert']['stock_min'], $arr_product_stockmin[$i]);

          $arr_product_id =  $_POST['productid'];


          $j = $j + 1;
          //$_SESSION['count_alert'] = $j;
          $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);
        }
      }
      echo '<script>location.href="order_from_shop.php";</script>';
    }
  }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Nouvelle Commande Pour <?php echo $_SESSION['magasin']; ?>
    </h1>
    <hr>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST">
        <div class="box-body">

          <div class="col-md-4">
            <div class="form-group">
              <label>Nom Operateur</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user"></i>
                </div>
                <input type="text" class="form-control pull-right" name="cashier_name" value="<?php 
                echo $_SESSION['user_name'];
                 ?>" readonly>
              </div>
            </div>
          </div>



          <div class="col-md-4">
            <div class="form-group">
              <label>Date de la transaction</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" name="orderdate" value="<?php echo date("d-m-Y"); ?>" readonly data-date-format="yyyy-mm-dd">
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Heures de transaction</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                </div>
                <input type="text" class="form-control pull-right" name="timeorder" value="<?php echo date('H:i') ?>" readonly>
              </div>
            </div>
          </div>
        </div>

        <div class="box-body">
          <div class="col-md-12" style="overflow-x:auto;">
            <table class="table table-border" id="myOrder">
              <thead>
                <tr>
                  <!--<th></th>-->
                  <th>Recherche</th>
                  <!--<th>Libelle Search</th>-->
                  <th>Code</th>
                  <th>Libelle</th>
                  <th>Stock</th>
                  <!--<th>Stock Min</th>-->
                  <th>Prix</th>
                  <th class="badge badge-warning">Prix Min</th>
                  <!-- <th>Rem %</th>
                  <th>Rem Val</th> -->
                  <th>Quantite</th>
                  <th>Unite</th>
                  <th>Total</th>
                  <th>
                    <button type="button" name="addOrder" class="btn btn-success btn-sm btn_addOrder" required><span>
                        <i class="fa fa-plus"></i>
                      </span></button>
                  </th>
                </tr>

              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
        <div class="box-body">
          <div class="col-md-offset-1 col-md-10">
            <div class="form-group">
              <label>Valeur Commande</label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="thetotal" id="thetotal" required readonly>
                <div class="input-group-addon">
                  <span>FCFA</span>
                </div>
              </div>
            </div>
            </div>
        </div>
        

         <!--    <div class="form-group">
              <label>TVA (19.25%) </label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="tva" id="tva" required readonly>
                <div class="input-group-addon">
                  <span>FCFA</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Remise</label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="remise" id="remise" required readonly>
                <div class="input-group-addon">
                  <span>FCFA</span>
                </div>
              </div>
            </div>


            <div class="form-group">
              <label>Total TTC A Payer</label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="total" id="total" required readonly>
                <div class="input-group-addon">
                  <span>FCFA</span>
                </div>
              </div>
            </div>


            <div class="form-group">
              <label>Argent recu</label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="paid" id="paid" required>
                <div class="input-group-addon">
                  <span>FCFA</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Remboursement</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="due" id="due" required readonly>
                <div class="input-group-addon">
                  <span><?php //echo $_SESSION['invoice_id']; ?>&nbsp; FCFA</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Mode Paiement</label>
              <select class="form-control" name="payment_mode" required>
                <option value="especes">Especes</option>
                <option value="orange_money">Orange Money</option>
                <option value="mtn_money">MTN Money</option>
                <option value="carte_visa">Carte Visa</option>
                <option value="virement">Virement</option>
              </select>
            </div>
          </div>
        </div> -->

        <div class="box-footer" align="center">
          <input type="submit" name="save_order" value="Enregistrer Operation" class="btn btn-success" onclick="return confirm('Are You Sure?')">
          <a href="order.php" class="btn btn-warning">Annuler</a>
        </div>
      </form>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>

</script>


<script>
  //Date picker
  $('#datepicker').datepicker({
    autoclose: true
  })

  //Timepicker
  $('.timepicker').timepicker({
    showInputs: false
  })

  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  })

  $(document).ready(function() {
    $(document).on('click', '.btn_addOrder', function() {
      var html = '';
      html += '<tr>';

    //html += '<td><select class="form-control productid" name="productid[]" style="width:220px;"><option value="">--Select Product--</option><?php
     //echo fill_product($pdo) ?></select></td>';
      html+='<td><input type="text" class="form-control productSearch" name="productSearch[]" style="width:220px;"><div class="productDropdown" style="position: absolute; z-index: 1000; display: none;"></div><input type="hidden" class="form-control productid" name="productid[]" value=""></td>';

      html += '<td><input type="text" class="form-control productcode" style="width:80px;" name="productcode[]" readonly></td>';
      html += '<td><input type="text" class="form-control productname" style="width:220px;" name="productname[]" readonly></td>';

      html += '<td><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" required readonly><input type="hidden" class="form-control minstock" style="width:50px;" name="minstock[]"></td>';

      html += '<td><input type="text" class="form-control productprice" style="width:90px;" name="productprice[]" readonly></td>';

      html += '<td><input type="text" class="form-control productmin btn btn-outline-dark" style="width:90px;" name="productmin[]" readonly></td>';

    //   html += '<td><input type="text" class="form-control discount" style="width:40px;" name="discount[]"></td>';

    //   html += '<td><input type="text" class="form-control remise" style="width:60px;" name="productremise[]" readonly></td>';

      html += '<td><input type="number" min="1" class="form-control quantity_product" style="width:70px;" name="quantity[]" required></td>';

      html += '<td><input type="text" class="form-control productsatuan" style="width:40px;" name="productsatuan[]" readonly></td>';

      html += '<td><input type="text" class="form-control producttotal" style="width:130px;" name="producttotal[]" readonly></td>';

      html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'
      html += '</tr>';

    $('#myOrder').append(html);

    var productIdField;  

    $(document).on('keyup', '.productSearch', function () {
        var query = $(this).val();
        var tr = $(this).closest('tr');
        var dropdown = tr.find('.productDropdown');
        productIdField = tr.find('.productid');  // Assign the value to productIdField

        $.ajax({
            url: 'get_products.php',
            method: 'POST',
            data: { query: query },
            success: function (data) {
                dropdown.html(data);
                dropdown.show();
            }
        });
    });

    $(document).on('click', '.productDropdown li', function () {
        var tr = $(this).closest('tr');
        var productName = $(this).text();
        var productId = $(this).data('product-id');
        tr.find('.productSearch').val(productName);
        tr.find('.productDropdown').hide();
        $.ajax({
          url: "get_product.php",
          method: "get",
          data: {
            id: productId
          },
          success: function(data) {
            tr.find(".productid").val(data["product_id"]);
            tr.find(".productcode").val(data["product_code"]);
            tr.find(".productname").val(data["product_name"]);
            tr.find(".productstock").val(data["stock"]);
            tr.find(".minstock").val(data["min_stock"]);
            tr.find(".productsatuan").val(data["product_satuan"]);
            tr.find(".productprice").val(data["sell_price"]);
            tr.find(".productmin").val(data["min_price"]);
            // tr.find(".discount").val(data["discount"]);
            tr.find(".quantity_product").val(0);
            tr.find(".producttotal").val(tr.find(".quantity_product").val() * tr.find(".productprice").val());
            calculate(0, 0);
          }
        })

    });


    })

    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculate(0, 0);
      $("#paid").val(0);
    })

    $("#myOrder").delegate(".quantity_product", "keyup change", function() {
      var quantity = $(this);
      var tr = $(this).parent().parent();
      if ((quantity.val() - 0) > (tr.find(".productstock").val() - 0)) {
        swal("Warning", "Stock Insuffisant", "warning");
        quantity.val(1);
        tr.find(".producttotal").val(Math.ceil((quantity.val() * tr.find(".productprice").val())));
        calculate(0, 0);
        // tr.find(".remise").val(Math.ceil((tr.find(".discount").val() / 100) * quantity.val() * tr.find(".productprice").val()));
        // calculate(0, 0);


      } else {
        tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
        tr.find(".producttotal").val(Math.ceil( quantity.val() * tr.find(".productprice").val()));
        calculate(0, 0);

        // tr.find(".remise").val(Math.ceil((tr.find(".discount").val() / 100) * quantity.val() * tr.find(".productprice").val()));
        // calculate(0, 0);

      }

      //}


    })


    $("#myOrder").delegate(".productprice", "keyup change", function() {
      var price = $(this);
      var pr = $(this).parent().parent();

      if ((price.val() - 0) < (pr.find(".productmin").val() - 0)) {
        swal("Warning", "Erreur Prix doit etre superieur ou egal a prix min", "warning");
        //quantity.val(1);
        //tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
        //calculate(0,0);
      }


    })



    function calculate(paid) {
      var net_total = 0;
      var paid = paid;
      var rem = 0;
      var thetotal = 0;
      var theTva = 0;
      var totalAll = 0;

      $(".producttotal").each(function() {
        net_total = net_total + ($(this).val() * 1);
      })

    //   $(".remise").each(function() {
    //     rem = rem + ($(this).val() * 1);
    //   })

    //   $(".producttotal").each(function() {
    //     theTva = theTva + ($(this).val() * 0.1925);
    //   })
      
      
      thetotal = thetotal + net_total ;
      theTva = thetotal * 0.1925;
      totalAll = totalAll+ net_total + theTva ;
      due = paid -totalAll ;
     
    

      $("#thetotal").val(thetotal);
    //   $("#total").val(totalAll);
    //   $("#due").val(due);
    //   $("#remise").val(rem);
    //   $("#tva").val(theTva);
    }


    $("#paid").keyup(function() {
      var paid = $(this).val();
      calculate(paid);
    })

  });
</script>


<?php
include_once 'inc/footer_all.php';
?>