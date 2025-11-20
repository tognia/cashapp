<?php
include_once 'db/connect_db.php';

// --- Role-Based Header Include and Session Check ---
if (empty($_SESSION['user_name'])) {
  header('location:index.php');
  exit();
} else {
  // Determine which header to include based on role
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}

// --- Insertion Logic (New Unit) ---
if (isset($_POST['submit'])) {
  $satuan = $_POST['satuan'] ?? ''; // Unit name from the form

  // Validation: Check if unit name is provided
  if (empty($satuan)) {
    echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Warning", "Unit name cannot be empty.", "warning", {
                button: "Continue",
                    });
                });
                </script>';
  } else {
    // IMPROVEMENT: Use Prepared Statements for SELECT (Crucial Security Fix)
    $select = $pdo->prepare("SELECT nm_satuan FROM tbl_satuan WHERE nm_satuan = :satuan");
    $select->bindParam(':satuan', $satuan);
    $select->execute();

    if ($select->rowCount() > 0) {
      echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Unité produit existante ou autre erreur", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
    } else {
      // IMPROVEMENT: Use Prepared Statements for INSERT (Security Fix)
      $insert = $pdo->prepare("INSERT INTO tbl_satuan(nm_satuan) VALUES(:satuan)");
      $insert->bindParam(':satuan', $satuan);

      if ($insert->execute()) {
        echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Unité produit enregistrée avec succès", "success", {
                        button: "Continue",
                            }).then((value) => {
                                // Reload page to update table
                                window.location.href = "satuan.php"; // Assuming this file is named satuan.php
                            });
                        });
                        </script>';
      } else {
        // Log or handle error if insert fails
        error_log("Database Insert Error: " . implode(", ", $insert->errorInfo()));
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Error", "Échec de l\'enregistrement de l\'unité.", "error", {
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
      Unités de Produit
      <small>Gestion des Unités de Mesure</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Unités</li>
    </ol>
  </section>

  <section class="content container-fluid">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Liste des Unités de Produit</h3>

        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewUnitModal">
          <i class="fa fa-plus"></i> Ajouter une Nouvelle Unité
        </button>
      </div>
      <div class="box-body" style="overflow-x:auto;">
        <table class="table table-bordered table-hover" id="mySatuan">
          <thead>
            <tr class="bg-primary">
              <th>No</th>
              <th>Libellé Unité produit</th>
              <th width="150">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $select = $pdo->prepare('SELECT * FROM tbl_satuan ORDER BY kd_satuan DESC');
            $select->execute();
            while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>
              <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo htmlspecialchars($row->nm_satuan); ?></td>
                <td>
                  <a href="edit_satuan.php?id=<?php echo $row->kd_satuan; ?>"
                    class="btn btn-info btn-sm" name="btn_edit" title="Modifier"><i class="fa fa-pencil"></i></a>

                  <button type="button" class="btn btn-danger btn-sm delete-btn"
                    data-id="<?php echo $row->kd_satuan; ?>" title="Supprimer">
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
<div class="modal fade" id="addNewUnitModal" tabindex="-1" role="dialog" aria-labelledby="addNewUnitModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addNewUnitModalLabel"><i class="fa fa-balance-scale"></i> Enregistrer une Nouvelle Unité</h4>
      </div>
      <form action="" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="satuan">Libellé unité</label>
            <input type="text" class="form-control" name="satuan" placeholder="Ex: Pièce, Kg, Litre" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-success" name="submit">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    // Initialize DataTables
    if ($.fn.DataTable) {
      $('#mySatuan').DataTable({
        "order": [
          [0, "desc"]
        ] // Sort by the first column (No/ID) descending
      });
    }

    // SweetAlert for Deletion Confirmation (IMPROVEMENT)
    $('.delete-btn').on('click', function(e) {
      e.preventDefault();
      var unitId = $(this).data('id');
      var deleteUrl = 'delete_satuan.php?id=' + unitId; // Assuming your delete script name

      swal({
          title: "Êtes-vous sûr(e)?",
          text: "Vous êtes sur le point de supprimer cette unité. Cette action est irréversible!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            // Redirect to the actual delete script upon confirmation
            window.location.href = deleteUrl;
          }
        });
    });
  });
</script>

<?php
include_once 'inc/footer_all.php';
?>