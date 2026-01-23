<?php
// add_damage.php
include_once 'db/connect_db.php';

// 1. Access Control
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

// 2. Header Selection based on Role
if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") {
    include_once 'inc/header_all.php';
} else {
    include_once 'inc/header_all_operator.php';
}

// 3. Logic to Process Damage Entry
if (isset($_POST['btn_save_damage'])) {
    $product_id      = $_POST['product_id'];
    $product_code    = $_POST['product_code'];
    $product_sku     = $_POST['product_sku'];
    $product_name    = $_POST['product_name'];
    $damaged_qty     = (int)$_POST['damaged_qty'];
    $damage_type     = $_POST['damage_type'];
    $code_agence     = $_POST['code_agence'];
    $user_id         = $_SESSION['user_id'];
    $damage_date     = date('Y-m-d');
    $notes           = $_POST['notes'];

    try {
        $pdo->beginTransaction();

        // Check if stock is sufficient before proceeding
        $checkStock = $pdo->prepare("SELECT stock FROM tbl_shop_item WHERE product_id = :p_id AND shop_code = :agence");
        $checkStock->execute([':p_id' => $product_id, ':agence' => $code_agence]);
        $current_stock = $checkStock->fetchColumn();

        if ($current_stock >= $damaged_qty) {
            // A. Insert into tbl_product_damage
            $insert = $pdo->prepare("INSERT INTO tbl_product_damage (damage_date, product_id, product_code, product_sku, product_name, damaged_quantity, code_agence, user_id, damage_type, notes) 
                                     VALUES (:d_date, :p_id, :p_code, :p_sku, :p_name, :qty, :agence, :user, :type, :notes)");

            $insert->execute([
                ':d_date' => $damage_date,
                ':p_id'   => $product_id,
                ':p_code' => $product_code,
                ':p_sku'  => $product_sku,
                ':p_name' => $product_name,
                ':qty'    => $damaged_qty,
                ':agence' => $code_agence,
                ':user'   => $_SESSION['user_name'],
                ':type'   => $damage_type,
                ':notes'  => $notes
            ]);

            // B. Deduct from tbl_shop_item stock
            $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock - :qty 
                                           WHERE product_id = :p_id AND shop_code = :agence");
            $update_stock->execute([':qty' => $damaged_qty, ':p_id' => $product_id, ':agence' => $code_agence]);

            $pdo->commit();
            echo '<script>jQuery(function(){ swal("Succès", "Dommage enregistré et stock mis à jour.", "success"); });</script>';
        } else {
            $pdo->rollBack();
            echo '<script>jQuery(function(){ swal("Erreur", "Stock insuffisant pour cette opération.", "error"); });</script>';
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo '<script>jQuery(function(){ swal("Erreur", "Une erreur technique est survenue.", "error"); });</script>';
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Gestion des Dommages
            <small>Signaler des pertes produits</small>
        </h1>
    </section>

    <section class="content container-fluid">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Formulaire de déclaration de dommage</h3>
            </div>

            <div class="box-body">
                <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE product_id = :id");
                    $select->execute([':id' => $id]);
                    $row = $select->fetch(PDO::FETCH_OBJ);

                    if ($row) {
                ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-solid box-default">
                                    <div class="box-header">
                                        <h3 class="box-title">Information Produit</h3>
                                    </div>
                                    <div class="box-body text-center">
                                        <img src="upload/<?php echo $row->img; ?>" class="img-responsive img-thumbnail" style="max-height: 200px;" alt="Product Image">
                                        <hr>
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <b>Code</b> <span class="pull-right label label-info"><?php echo $row->product_code; ?></span>
                                            </li>
                                            <li class="list-group-item">
                                                <b>SKU</b> <span class="pull-right label label-default"><?php echo $row->product_sku; ?></span>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Stock Actuel</b> <span class="pull-right label label-primary" style="font-size: 14px;"><?php echo $row->stock; ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <form action="" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>">
                                    <input type="hidden" name="product_code" value="<?php echo $row->product_code; ?>">
                                    <input type="hidden" name="product_sku" value="<?php echo $row->product_sku; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $row->product_name; ?>">
                                    <input type="hidden" name="code_agence" value="<?php echo $row->shop_code; ?>">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nature du Dommage</label>
                                                <select class="form-control" name="damage_type" required>
                                                    <option value="">-- Sélectionner le type --</option>
                                                    <option value="Broken">Cassé (Broken)</option>
                                                    <option value="Expired">Expiré (Expired)</option>
                                                    <option value="Water Damage">Dégât des eaux (Water Damage)</option>
                                                    <option value="Defective">Défectueux (Defective)</option>
                                                    <option value="Other">Autre</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Quantité Affectée</label>
                                                <input type="number" name="damaged_qty" class="form-control" min="1" max="<?php echo $row->stock; ?>" placeholder="Qté" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description / Notes</label>
                                        <textarea name="notes" class="form-control" rows="5" placeholder="Expliquez brièvement les circonstances du dommage..."></textarea>
                                    </div>

                                    <div class="form-group">
                                        <hr>
                                        <button type="submit" name="btn_save_damage" class="btn btn-danger btn-lg" onclick="return confirm('Attention: Cette action va déduire le stock de la boutique. Continuer ?')">
                                            <i class="fa fa-exclamation-triangle"></i> Confirmer l'enregistrement
                                        </button>
                                        <a href="view_product_shop.php?id=<?php echo $id; ?>" class="btn btn-default btn-lg pull-right">Annuler</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                <?php
                    } else {
                        echo '<div class="alert alert-warning">Produit non trouvé.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">ID de produit manquant.</div>';
                }
                ?>
            </div>

            <div class="box-footer">
                <p class="text-muted"><i class="fa fa-info-circle"></i> Toutes les déclarations de dommages sont enregistrées avec l'utilisateur et l'heure actuelle pour l'audit.</p>
            </div>
        </div>
    </section>
</div>

<?php
include_once 'inc/footer_all.php';
?>