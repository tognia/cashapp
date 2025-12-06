<?php
include_once 'db/connect_db.php';
if ($_SESSION['role'] !== "Admin") {
  header('location:index.php');
}

if (isset($_POST['btn_edit'])) {

  $code_agence = $_POST['code_agence'];
  $libelle_agence = $_POST['libelle_agence'];
  $email = $_POST['email'];
  $tel = $_POST['tel'];
  $ville = $_POST['ville'];

  $update = $pdo->prepare("UPDATE agence SET code_agence='$code_agence', libelle_agence ='$libelle_agence', email = '$email', tel = '$tel', ville = '$ville'   WHERE id='" . $_GET['id'] . "' ");
  $update->bindParam(':code_agence', $code_agence);
  if ($update->execute()) {
    echo '<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Boutique Has Been Updated", "success", {
        button: "Continue",
            });
        });
        </script>';
  } else {
    echo '<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Boutique Mise a jour", "success", {
        button: "Continue",
            });
        });
        </script>';
  }
}

if ($id = $_GET['id']) {
  $select = $pdo->prepare("SELECT * FROM agence WHERE id = '" . $_GET['id'] . "' ");
  $select->execute();
  $row = $select->fetch(PDO::FETCH_OBJ);
  $code_agence = $row->code_agence;
  $libelle_agence = $row->libelle_agence;
  $email = $row->email;
  $tel = $row->tel;
  $ville = $row->ville;
} else {
  header('location:agence.php');
}

include_once 'inc/header_all.php';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Boutique
    </h1>
    <hr>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <!-- agence Form-->
    <div class="col-md-4">
      <div class="box box-warning">
        <!-- /.box-header -->
        <!-- form start -->
        <form action="" method="POST">
          <div class="box-body">
            <div class="form-group">
              <label for="code_agence">Code Boutique </label>
              <input type="text" class="form-control" name="code_agence" placeholder="Enter agence"
                value="<?php echo $code_agence; ?>" required>
            </div>
            <div class="form-group">
              <label for="libelle_agence">Libelle Boutique </label>
              <input type="text" class="form-control" name="libelle_agence" placeholder="Enter Libelle Boutique"
                value="<?php echo $libelle_agence; ?>" required>
            </div>
            <div class="form-group">
              <label for="email">Email </label>
              <input type="text" class="form-control" name="email" placeholder="Enter email"
                value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
              <label for="tel">Tel </label>
              <input type="text" class="form-control" name="tel" placeholder="Enter tel"
                value="<?php echo $tel; ?>" required>
            </div>

            <div class="form-group">
              <label>Ville</label>
              <select class="form-control" name="ville" required>
                <option selected="selected"><?php echo $ville; ?></option>
                <option>Yaounde</option>
                <option>Douala</option>
                <option>Bafoussam</option>
                <option>Bangangte</option>
                <option>Paris</option>
              </select>
            </div>

          </div><!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary" name="btn_edit">Update</button>
            <a href="agence.php" class="btn btn-warning">Back</a>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-8">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Liste Boutique</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Code Boutique</th>
                <th>Libelle Boutique</th>
                <th>Email</th>
                <th>Tel</th>
                <th>Ville</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $select = $pdo->prepare('SELECT * FROM agence');
              $select->execute();
              while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>
                <tr>
                  <td><?php echo $row->id; ?></td>
                  <td><?php echo $row->code_agence; ?></td>
                  <td><?php echo $row->libelle_agence; ?></td>
                  <td><?php echo $row->email; ?></td>
                  <td><?php echo $row->tel; ?></td>
                  <td><?php echo $row->ville; ?></td>
                </tr>
              <?php
              }
              ?>

            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include_once 'inc/footer_all.php';
?>