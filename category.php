<?php
include_once 'db/connect_db.php';

// Check user role for access control
if ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable") {
  header('location:index.php');
  exit(); // Always use exit after a header redirect
}

include_once 'inc/header_all.php';

// --- Insertion Logic (New Category) ---
if (isset($_POST['submit'])) {

  $category = $_POST['category'] ?? ''; // Category name from the form
  $categoryparent = $_POST['categoryparent'] ?? 'Aucune';
  $level = 3; // Hardcoded as per original requirement

  // Validation: Check if category name is provided
  if (empty($category)) {
    echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Warning", "Category name cannot be empty.", "warning", {
                button: "Continue",
                    });
                });
                </script>';
  } else {

    // IMPROVEMENT: Use Prepared Statements for SELECT (Security fix)
    $select = $pdo->prepare("SELECT cat_name FROM tbl_category WHERE cat_name = :category");
    $select->bindParam(':category', $category);
    $select->execute();

    if ($select->rowCount() > 0) {
      echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Catégorie Existante ou Autre Problème", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
    } else {
      // IMPROVEMENT: Use Prepared Statements for INSERT (Security fix)
      $insert = $pdo->prepare("INSERT INTO tbl_category(cat_name, cat_parent, cat_level)
                                     VALUES(:category, :categoryparent, :level)");

      $insert->bindParam(':category', $category);
      $insert->bindParam(':categoryparent', $categoryparent);
      $insert->bindParam(':level', $level, PDO::PARAM_INT);

      if ($insert->execute()) {
        echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Catégorie enregistrée avec succès", "success", {
                        button: "Continue",
                            }).then((value) => {
                                // Reload page to update table without manual refresh
                                window.location.href = "category.php";
                            });
                        });
                        </script>';
      } else {
        error_log("Database Insert Error: " . implode(", ", $insert->errorInfo()));
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Error", "Failed to register category.", "error", {
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
      Catégories
      <small>Gestion des Catégories de Produits</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Catégories</li>
    </ol>
  </section>

  <section class="content container-fluid">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Liste des Catégories</h3>

        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewCategoryModal">
          <i class="fa fa-plus"></i> Ajouter une Nouvelle Catégorie
        </button>
      </div>
      <div class="box-body" style="overflow-x:auto;">
        <table class="table table-bordered table-hover" id="myCategory">
          <thead>
            <tr class="bg-primary">
              <th>ID</th>
              <th>Libellé</th>
              <th>Catégorie Parent</th>
              <?php if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "Responsable") { ?>
                <th width="150">Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $select = $pdo->prepare('SELECT * FROM tbl_category ORDER BY cat_id DESC');
            $select->execute();
            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
              $is_admin = $_SESSION["role"] == "Admin";
              $is_responsable = $_SESSION["role"] == "Responsable";
            ?>
              <tr>
                <td><?php echo $row->cat_id; ?></td>
                <td><?php echo htmlspecialchars($row->cat_name); ?></td>
                <td><?php echo htmlspecialchars($row->cat_parent); ?></td>

                <?php if ($is_admin || $is_responsable) { ?>
                  <td>
                    <a href="edit_category.php?id=<?php echo $row->cat_id; ?>" class="btn btn-info btn-sm" name="btn_edit" title="Modifier"><i class="fa fa-pencil"></i></a>
                    <?php if ($is_admin) { ?>
                      <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row->cat_id; ?>" title="Supprimer">
                        <i class="fa fa-trash"></i>
                      </button>
                    <?php } ?>
                  </td>
                <?php } ?>
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
<div class="modal fade" id="addNewCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addNewCategoryModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addNewCategoryModalLabel"><i class="fa fa-tags"></i> Enregistrer une Nouvelle Catégorie</h4>
      </div>
      <form action="" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="category">Libellé</label>
            <input type="text" class="form-control" name="category" placeholder="Entrer le nom de la catégorie" required>
          </div>
          <div class="form-group">
            <label for="categoryparent">Catégorie Parent</label>
            <select class="form-control" name="categoryparent">
              <option value="Aucune">Aucune</option>
              <?php
              // Re-fetch categories for the modal dropdown
              $select = $pdo->prepare("SELECT cat_name FROM tbl_category");
              $select->execute();
              while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
              ?>
                <option value="<?php echo htmlspecialchars($row['cat_name']); ?>"><?php echo htmlspecialchars($row['cat_name']); ?></option>
              <?php
              }
              ?>
            </select>
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
      $('#myCategory').DataTable({
        "order": [
          [0, "desc"]
        ] // Sort by ID descending
      });
    }

    // SweetAlert for Deletion Confirmation
    $('.delete-btn').on('click', function(e) {
      e.preventDefault();
      var categoryId = $(this).data('id');
      var deleteUrl = 'delete_category.php?id=' + categoryId; // Assuming your delete script name

      swal({
          title: "Êtes-vous sûr(e)?",
          text: "Vous êtes sur le point de supprimer cette catégorie. Cette action est irréversible!",
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