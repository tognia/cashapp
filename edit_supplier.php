<?php
include_once 'misc/plugin.php';
include_once 'db/connect_db.php';
if ($_SESSION['role'] !== "Admin") {
    header('location:index.php');
}

if ($id = $_GET['id']) {
    $select = $pdo->prepare("SELECT * FROM supliers WHERE suplier_id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $suplier_id = $row['suplier_id'];
    $suplier_name = $row['suplier_name'];
    $suplier_address = $row['suplier_address'];
    $suplier_contact = $row['suplier_contact'];
    $contact_person = $row['contact_person'];
    $note = $row['note'];
} else {
    header('location:supplier.php');
}

if (isset($_POST['submit'])) {

    $suplier_id = $_POST['suplier_id'];
    $suplier_name = $_POST['suplier_name'];
    $suplier_address = $_POST['suplier_address'];
    $suplier_contact = $_POST['suplier_contact'];
    $contact_person = $_POST['contact_person'];
    $note = $_POST['note'];


    $update = $pdo->prepare("UPDATE supliers SET suplier_id=:suplier_id,suplier_name=:suplier_name,
                                suplier_address=:suplier_address, suplier_contact=:suplier_contact, contact_person=:contact_person,
                                note=:note WHERE suplier_id = $id");

    $update->bindParam('suplier_id', $suplier_id);
    $update->bindParam('suplier_name', $suplier_name);
    $update->bindParam('suplier_address', $suplier_address);
    $update->bindParam('suplier_contact', $suplier_contact);
    $update->bindParam('contact_person', $contact_person);
    $update->bindParam('note', $note);


    if ($update->execute()) {
        echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Modification reussie", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
        header('location:view_supplier.php?id=' . urlencode($id));
    } else {
        echo 'Something is Wrong';
    }
}



include_once 'inc/header_all.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

        </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Modifier Fournisseur</h3>
            </div>
            <form action="" method="POST" name="form_supplier"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Code Fournisseur</label>
                            <input type="text" class="form-control"
                                name="suplier_id" value="<?php echo $suplier_id; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Nom Fournisseur</label>
                            <input type="text" class="form-control"
                                name="suplier_name" value="<?php echo $suplier_name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Adresse</label>
                            <input type="text" class="form-control"
                                name="suplier_address" value="<?php echo $suplier_address; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="">Contact Fournisseur</label>
                            <!--<input type="number" min="10" step="100"-->
                            <input type="text" min="10" class="form-control"
                                name="suplier_contact" value="<?php echo $suplier_contact; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Personnel</label>
                            <input type="text" min="10" class="form-control"
                                name="contact_person" value="<?php echo $contact_person; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Note</label>
                            <input type="number" min="1" step="1"
                                class="form-control" name="note" value="<?php echo $note; ?>" required>
                        </div>



                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="submit">Enregistrer</button>
                        <a href="supplier.php" class="btn btn-warning">Retour</a>
                    </div>
            </form>

        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'inc/footer_all.php';
?>