<?php
include_once 'db/connect_db.php';
session_start();
if ($_SESSION['user_name'] == "") {
  include_once 'inc/404.php';
} else {
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}

error_reporting(0);
date_default_timezone_set('Africa/Douala');
$shop = $_SESSION['magasin'];

function fill_product($pdo)
{
  $output = '';

  $s = $_SESSION['magasin'];
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
  $id_client = $_POST['client'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i", strtotime($_POST['timeorder']));
  $total = $_POST['total'];
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $remise = $_POST['remise'];


  $arr_product_id =  $_POST['productid'];
  $arr_product_code = $_POST['productcode'];
  $arr_product_name = $_POST['productname'];
  $arr_product_stock = $_POST['productstock'];
  $arr_product_stockmin = $_POST['minstock'];
  $arr_product_qty = $_POST['quantity'];
  $arr_product_satuan = $_POST['productsatuan'];
  $arr_product_price = $_POST['productprice'];
  $arr_product_min = $_POST['productmin'];
  $arr_discount = $_POST['discount'];
  $arr_product_total =  $_POST['producttotal'];
  $arr_product_remise =  $_POST['productremise'];
  echo $arr_product_code." ".  $arr_product_name;
  if ($arr_product_code == "") {
    echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Veuillez remplir le formulaire de transaction", "warning", {
              button: "Continue",
                  });
              });
              </script>';
  } else {


    $insert = $pdo->prepare("INSERT INTO tbl_invoice(cashier_name, id_client, order_date, time_order, total, paid, due, remise)
        values(:name, :id_client, :orderdate, :timeorder, :total, :paid, :due, :remise)");

    $insert->bindParam(':name', $cashier_name);
    $insert->bindParam(':id_client', $id_client);
    $insert->bindParam(':orderdate',  $order_date);
    $insert->bindParam(':timeorder',  $order_time);
    $insert->bindParam(':total', $total);
    $insert->bindParam(':paid', $paid);
    $insert->bindParam(':due', $due);
    $insert->bindParam(':remise', $remise);

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
        } else if ($diff < 0) {

          echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Prix Min SUPERIEUR A PRIX CONFORME", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
          $update = $pdo->prepare("UPDATE tbl_shop_item SET stock = '$rem_qty' WHERE shop_code= '$shop' AND product_id='" . $arr_product_id[$i] . "'");
          $update->execute();
        }


        $insert = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, remise)
            values(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate, :remise)");

        $insert->bindParam(':invid',  $invoice_id);
        $insert->bindParam(':productid',   $arr_product_id[$i]);
        $insert->bindParam(':productcode',   $arr_product_code[$i]);
        $insert->bindParam(':productname', $arr_product_name[$i]);
        $insert->bindParam(':qty', $arr_product_qty[$i]);
        $insert->bindParam(':productsatuan', $arr_product_satuan[$i]);
        $insert->bindParam(':price',  $arr_product_price[$i]);
        $insert->bindParam(':total',   $arr_product_total[$i]);
        $insert->bindParam(':orderdate',  $order_date);
        $insert->bindParam(':remise',  $arr_product_remise[$i]);
        $insert->execute();


        if ($reste <= 0) {

          array_push($_SESSION['tab_alert']['id'], $arr_product_id[$i]);
          array_push($_SESSION['tab_alert']['code'], $arr_product_code[$i]);
          array_push($_SESSION['tab_alert']['name'], $arr_product_name[$i]);
          array_push($_SESSION['tab_alert']['stock'], $rem_qty);
          array_push($_SESSION['tab_alert']['stock_min'], $arr_product_stockmin[$i]);

          $arr_product_id =  $_POST['productid'];


          $j = $j + 1;
          $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);
        }
      }
      echo '<script>location.href="order.php";</script>';
    }
  }
}

?>

<div class="content-wrapper">
   <section class="content-header">
    <h1>
      Transaction
    </h1>
    <hr>
    
  </section>

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
                <input type="text" class="form-control pull-right" name="cashier_name" value="<?php echo $_SESSION['user_name']; ?>" readonly>
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

        <div class="col-md-4">
          <div class="form-group">
            <label for="">Client</label>
            <select class="form-control" name="client" required>
              <option>common</option>
              <?php
              $select = $pdo->prepare("SELECT * FROM users ORDER BY firstname");
              $select->execute();
              while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                extract($row)
              ?>
                <option value="<?php echo $row['username']; ?>"><?php echo $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; ?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <center>
    <!-- <div class="search-box u-align-right">
        <input type="text" autocomplete="off" placeholder="Recherche Produit" />
        <div class="result"></div> -->
  </div>
  </center><br><br>   
 
        <div class="box-body">
          <div class="col-md-12" style="overflow-x:auto;">
            <table class="table table-border" id="myOrder">
              <thead>
                <tr>
                  <th>Code Search</th>
                  <th>Code</th>
                  <th>Libelle</th>
                  <th>Stock</th>
                  <th>Prix</th>
                  <th class="badge badge-warning">Prix Min</th>
                  <th>Rem %</th>
                  <th>Rem Val</th>
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
              <label>Total</label>
              <div class="input-group">

                <input type="text" class="form-control pull-right" name="thetotal" id="thetotal" required readonly>
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
              <label>Total A Payer</label>
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
                  <span><?php echo $_SESSION['invoice_id']; ?>&nbsp; FCFA</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box-footer" align="center">
          <input type="submit" name="save_order" value="Enregistrer Operation" class="btn btn-success" onclick="return confirm('Are You Sure?')">
          <a href="order.php" class="btn btn-warning">Annuler</a>
        </div>
      </form>

    </div>
  </section>

</div>


<script>
  $('#datepicker').datepicker({
    autoclose: true
  })

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
      html += '<td><select class="form-control productid" name="productid[]" style="width:220px;"><option value="">--Select Product--</option><?php  echo fill_product($pdo) ?></select></td>';
      html += '<td><input type="text" class="form-control productcode" style="width:80px;" name="productcode[]" readonly></td>';
      html += '<td><input type="text" class="form-control productname" style="width:220px;" name="productname[]" readonly></td>';
      html += '<td><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" required readonly><input type="hidden" class="form-control minstock" style="width:50px;" name="minstock[]"></td>';
      html += '<td><input type="text" class="form-control productprice" style="width:90px;" name="productprice[]"></td>';
      html += '<td><input type="text" class="form-control productmin btn btn-outline-dark" style="width:90px;" name="productmin[]" readonly></td>';
      html += '<td><input type="text" class="form-control discount" style="width:40px;" name="discount[]"></td>';
      html += '<td><input type="text" class="form-control remise" style="width:60px;" name="productremise[]" readonly></td>';
      html += '<td><input type="number" min="1" class="form-control quantity_product" style="width:70px;" name="quantity[]" required></td>';
      html += '<td><input type="text" class="form-control productsatuan" style="width:40px;" name="productsatuan[]" readonly></td>';
      html += '<td><input type="text" class="form-control producttotal" style="width:130px;" name="producttotal[]" readonly></td>';
      html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'
      html += '</tr>';

      $('#myOrder').append(html);

      $('.productid').on('change', function(e) {
        var productid = this.value;
        var tr = $(this).parent().parent();
        $.ajax({
          url: "getproduct.php",
          method: "get",
          data: {
            id: productid
          },
          success: function(data) {
            tr.find(".productcode").val(data["product_code"]);
            tr.find(".productname").val(data["product_name"]);
            tr.find(".productstock").val(data["stock"]);
            tr.find(".minstock").val(data["min_stock"]);
            tr.find(".productsatuan").val(data["product_satuan"]);
            tr.find(".productprice").val(data["sell_price"]);
            tr.find(".productmin").val(data["min_price"]);
            tr.find(".discount").val(data["discount"]);
            tr.find(".quantity_product").val(0);
            tr.find(".producttotal").val(tr.find(".quantity_product").val() * tr.find(".productprice").val());
            calculate(0, 0);
          }
        })
      })

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
        tr.find(".producttotal").val(Math.ceil((1 - (tr.find(".discount").val() / 100)) * quantity.val() * tr.find(".productprice").val()));
        calculate(0, 0);
        tr.find(".remise").val(Math.ceil((tr.find(".discount").val() / 100) * quantity.val() * tr.find(".productprice").val()));
        calculate(0, 0);


      } else {
        tr.find(".producttotal").val(Math.ceil((1 - (tr.find(".discount").val() / 100)) * quantity.val() * tr.find(".productprice").val()));
        calculate(0, 0);

        tr.find(".remise").val(Math.ceil((tr.find(".discount").val() / 100) * quantity.val() * tr.find(".productprice").val()));
        calculate(0, 0);

      }

    })


    $("#myOrder").delegate(".productprice", "keyup change", function() {
      var price = $(this);
      var pr = $(this).parent().parent();

      if ((price.val() - 0) < (pr.find(".productmin").val() - 0)) {
        swal("Warning", "Erreur Prix doit etre superieur ou egal a prix min", "warning");
      }


    })



    function calculate(paid) {
      var net_total = 0;
      var paid = paid;
      var rem = 0;
      var thetotal = 0;

      $(".producttotal").each(function() {
        net_total = net_total + ($(this).val() * 1);
      })

      $(".remise").each(function() {
        rem = rem + ($(this).val() * 1);
      })

      due = net_total - paid;
      thetotal = thetotal + net_total + rem;
      // remise = 

      $("#thetotal").val(thetotal);
      $("#total").val(net_total);
      $("#due").val(due);
      $("#remise").val(rem);
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