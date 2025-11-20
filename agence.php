<?php
include_once 'db/connect_db.php';
// Check if session has started and role is set
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
  header('location:index.php');
  exit(); // Always exit after a header redirect
}
include_once 'inc/header_all.php';

// --- Deletion Logic ---
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Improved: Use prepared statements for DELETE to prevent SQL injection
  $delete = $pdo->prepare("DELETE FROM agence WHERE id = :id");
  $delete->bindParam(':id', $id, PDO::PARAM_INT);

  if ($delete->execute()) {
    // Swal notification
    echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Shop Has Been Deleted", "info", {
                button: "Continue",
                    });
                });
                </script>';
  }
}

// --- Insertion Logic ---
if (isset($_POST['submit'])) {
  // Sanitizing and validation should be added here for a robust application
  $code_agence    = $_POST['code_agence'];
  $libelle_agence = $_POST['libelle_agence'];
  $email          = $_POST['email'];
  $tel            = $_POST['tel'];
  $ville          = $_POST['ville'];

  if (isset($_POST['code_agence'])) {
    // Use prepared statements for SELECT
    $select = $pdo->prepare("SELECT code_agence FROM agence WHERE code_agence = :code_agence");
    $select->bindParam(':code_agence', $code_agence);
    $select->execute();

    if ($select->rowCount() > 0) {
      // Swal warning
      echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Magasin Existant ou Autre Probleme", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
    } else {
      // Use prepared statements for INSERT
      $insert = $pdo->prepare("INSERT INTO agence(code_agence,libelle_agence,email,tel,ville) VALUES(:code_agence,:libelle_agence,:email,:tel,:ville)");

      $insert->bindParam(':code_agence', $code_agence);
      $insert->bindParam(':libelle_agence', $libelle_agence);
      $insert->bindParam(':email', $email);
      $insert->bindParam(':tel', $tel);
      $insert->bindParam(':ville', $ville);


      if ($insert->execute()) {
        // Swal success
        echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Magasin enregistré avec succes", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
      }
    }
  }
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Shop Management
      <small>List of Registered Shops</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Shops</li>
    </ol>
  </section>

  <section class="content container-fluid">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Shops (Agences)</h3>
        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewShopModal">
          <i class="fa fa-plus"></i> Add New Shop
        </button>
      </div>
      <div class="box-body" style="overflow-x:auto;">
        <table class="table table-bordered table-hover" id="mycode_agence">
          <thead>
            <tr class="bg-primary">
              <th>No</th>
              <th>Code</th>
              <th>Agence</th>
              <th>Email</th>
              <th>Tel</th>
              <th>Ville</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $select = $pdo->prepare('SELECT * FROM agence ORDER BY id DESC'); // Display newest first
            $select->execute();
            $counter = 1; // Use a counter for 'No' instead of DB ID
            while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>
              <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo $row->code_agence; ?></td>
                <td><?php echo $row->libelle_agence; ?></td>
                <td><?php echo $row->email; ?></td>
                <td><?php echo $row->tel; ?></td>
                <td><?php echo $row->ville; ?></td>
                <td>
                  <a href="edit_agence.php?id=<?php echo $row->id; ?>"
                    class="btn btn-info btn-sm" name="btn_edit" title="Edit"><i class="fa fa-pencil"></i></a>
                  <button type="button" class="btn btn-danger btn-sm delete-btn"
                    data-id="<?php echo $row->id; ?>" title="Delete">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
<div class="modal fade" id="addNewShopModal" tabindex="-1" role="dialog" aria-labelledby="addNewShopModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addNewShopModalLabel"><i class="fa fa-building"></i> Add New Shop</h4>
      </div>
      <form action="" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="code_agence">Shop Code</label>
            <input type="text" class="form-control" name="code_agence" placeholder="Enter Shop Code" required>
          </div>
          <div class="form-group">
            <label for="libelle_agence">Shop Name</label>
            <input type="text" class="form-control" name="libelle_agence" placeholder="Enter Shop Name" required>
          </div>
          <div class="form-group">
            <label for="email">Shop Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter Email">
          </div>
          <div class="form-group">
            <label for="tel">Tel</label>
            <input type="text" class="form-control" name="tel" placeholder="Enter Telephone">
          </div>
          <div class="form-group">
            <label>Ville</label>
            <select class="form-control" name="ville" required>
              <option value="">Select a City</option>
              <option>Yaounde</option>
              <option>Douala</option>
              <option>Bafoussam</option>
              <option>Bangangte</option>
              <option>Paris</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" name="submit">Save Shop</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    // Initialize DataTables
    $('#mycode_agence').DataTable({
      "order": [
        [0, "desc"]
      ] // Sort by 'No' column (which reflects ID) descending by default
    });

    // Use swal for deletion confirmation instead of built-in confirm()
    $('.delete-btn').on('click', function(e) {
      e.preventDefault();
      var shopId = $(this).data('id');
      var deleteUrl = 'agence.php?id=' + shopId;

      swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this shop record!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = deleteUrl;
          }
        });
    });
  });
</script>

<?php
include_once 'inc/footer_all.php';
?>