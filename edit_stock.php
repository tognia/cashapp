<?php

include_once 'misc/plugin.php';
include_once 'db/connect_db.php';

// --- 1. Contrôle d'Accès et Redirection ---
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable" && $_SESSION['role'] !== "storekeeper")) {
    header('Location: index.php');
    exit();
}

// --- 2. Initialisation et Récupération des Données ---
$id = $_GET['id'] ?? null;
$message = '';

if (!$id || !is_numeric($id)) {
    header('Location: product.php');
    exit();
}

try {
    // Récupérer les données du produit
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id = :id");
    $select->bindParam(':id', $id, PDO::PARAM_INT);
    $select->execute();
    $product_data = $select->fetch(PDO::FETCH_ASSOC);

    if (!$product_data) {
        header('Location: product.php');
        exit();
    }

    // Assignation des variables
    $id_db = $product_data['product_id'];
    $productCode_db = $product_data['product_code'];
    $productSku_db = $product_data['product_sku'];
    $productName_db = $product_data['product_name'];
    $category_db = $product_data['product_category'];
    $productBrand_db = $product_data['product_brand'];
    $purchase_db = $product_data['purchase_price'];
    $sell_db = $product_data['sell_price'];
    $min_db = $product_data['min_price'];
    $discount_db = $product_data['discount'];
    $stock_db = $product_data['stock']; // Stock actuel
    $min_stock_db = $product_data['min_stock'];
    $satuan_db = $product_data['product_satuan'];
    $supplier_db = $product_data['supplier']; // Fournisseur par défaut du produit
    $desc_db = $product_data['description'];
    $product_img = $product_data['img'];
    $stock_db_current = $stock_db;

    // Récupérer l'ID de l'utilisateur connecté
    $current_user_id = $_SESSION['user_id'] ?? null;

    // Récupérer la liste des fournisseurs (table 'supplier')
    $select_suppliers = $pdo->query("SELECT suplier_name FROM supliers ORDER BY suplier_name ASC");
    $suppliers_list = $select_suppliers->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Database Error (Fetch): " . $e->getMessage());
    $message = '<div class="alert alert-danger">Une erreur inattendue est survenue lors de la récupération des données.</div>';
}

// --- 3. Gestion de la Soumission du Formulaire (Transaction) ---
if (isset($_POST['update_product'])) {

    // 3.1. Nettoyage et Validation des entrées
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $received_quantity = filter_input(INPUT_POST, 'received_quantity', FILTER_SANITIZE_NUMBER_INT);
    $receipt_price = filter_input(INPUT_POST, 'receipt_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $supplier_name = filter_input(INPUT_POST, 'supplier_name', FILTER_SANITIZE_STRING);
    $receipt_date = filter_input(INPUT_POST, 'receipt_date', FILTER_SANITIZE_STRING);

    if (!$product_id || !is_numeric($product_id) || !is_numeric($received_quantity) || $received_quantity < 1) {
        $message = '<div class="alert alert-danger">Quantité reçue invalide (minimum 1).</div>';
    } elseif (!is_numeric($receipt_price) || $receipt_price < 0) {
        $message = '<div class="alert alert-danger">Prix d\'achat reçu invalide.</div>';
    } elseif (!$receipt_date) {
        $message = '<div class="alert alert-danger">Date de réception requise.</div>';
    } elseif (!$current_user_id) {
        $message = '<div class="alert alert-danger">Erreur: Identifiant utilisateur non disponible.</div>';
    } else {
        try {
            // Démarrer la transaction
            $pdo->beginTransaction();

            // 3.2. Insertion dans tbl_product_receipt
            $insert_receipt = $pdo->prepare("
                INSERT INTO tbl_product_receipt (receipt_date, product_id, received_quantity, supplier_name, receipt_price, user_id)
                VALUES (:date, :product_id, :quantity, :supplier, :price, :user_id)
            ");

            $insert_receipt->bindParam(':date', $receipt_date);
            $insert_receipt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $insert_receipt->bindParam(':quantity', $received_quantity, PDO::PARAM_INT);
            $insert_receipt->bindParam(':supplier', $supplier_name);
            $insert_receipt->bindParam(':price', $receipt_price);
            $insert_receipt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);

            if ($insert_receipt->execute()) {

                // 3.3. Mise à jour du stock dans tbl_product
                $new_total_stock = $stock_db_current + $received_quantity;

                $update_stock = $pdo->prepare("UPDATE tbl_product SET stock = :stock WHERE product_id = :id");
                $update_stock->bindParam(':stock', $new_total_stock, PDO::PARAM_INT);
                $update_stock->bindParam(':id', $product_id, PDO::PARAM_INT);

                if ($update_stock->execute()) {
                    // 3.4. Valider la transaction
                    $pdo->commit();

                    // Redirection sur succès
                    header('Location: view_product.php?id=' . urlencode($product_id));
                    exit();
                } else {
                    // Annuler l'insertion de la réception
                    $pdo->rollBack();
                    $message = '<div class="alert alert-danger">Erreur lors de la mise à jour du stock principal (Rollback effectué).</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">Erreur lors de l\'enregistrement de la réception.</div>';
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de transaction
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Transaction Error: " . $e->getMessage());
            $message = '<div class="alert alert-danger">Une erreur base de données est survenue. L\'opération a été annulée.</div>';
        }
    }
}

// --- 4. Affichage HTML ---
include_once 'inc/header_all.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus-square"></i> Ajouter du Stock et Enregistrer la Réception
        </h1>
    </section>

    <section class="content container-fluid">

        <?php if ($message) {
            echo $message;
        } ?>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Augmenter le Stock de **<?php echo htmlspecialchars($productName_db); ?>**</h3>
            </div>

            <form action="" method="POST" id="productForm" onsubmit="return confirmAddition();"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <h4 class="text-success">Informations Produit</h4>
                            <hr style="margin-top: 5px;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id_db); ?>">

                            <div class="form-group">
                                <label>Code Produit</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($productCode_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($productSku_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Libellé Produit</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($productName_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Catégorie</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($category_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Marque</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($productBrand_db); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h4 class="text-success">Stock Actuel et Détails de Réception</h4>
                            <hr style="margin-top: 5px;">

                            <div class="form-group">
                                <label>Stock Actuel (Magasin Principal)</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($stock_db_current); ?>" readonly>
                            </div>

                            <div class="form-group bg-info" style="padding: 10px; border-radius: 4px;">
                                <label for="received_quantity" style="color: #0b2e13;">Quantité Reçue à Ajouter <span class="text-danger">*</span></label>
                                <input type="number" id="received_quantity" min="1" step="1"
                                    class="form-control input-lg" name="received_quantity" required
                                    placeholder="Quantité de la livraison"
                                    style="font-size: 1.5em;">
                            </div>

                            <div class="form-group">
                                <label for="receipt_date">Date de Réception <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="receipt_date" id="receipt_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="receipt_price">Prix d'Achat Unitaire Reçu <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" name="receipt_price" id="receipt_price"
                                    placeholder="Ex: <?php echo htmlspecialchars($purchase_db); ?>" value="<?php echo htmlspecialchars($purchase_db); ?>" required>
                                <span class="help-block">Prix unitaire payé.</span>
                            </div>

                            <div class="form-group">
                                <label for="supplier_name">Fournisseur <span class="text-info">(Optionnel)</span></label>
                                <select class="form-control" name="supplier_name" id="supplier_name" style="width: 100%;">
                                    <option value="">-- Aucun / Non spécifié --</option>
                                    <?php foreach ($suppliers_list as $supp) : ?>
                                        <option value="<?php echo htmlspecialchars($supp); ?>"
                                            <?php echo ($supp == $supplier_db) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($supp); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h4 class="text-success">Visuel et Description</h4>
                            <hr style="margin-top: 5px;">
                            <div class="form-group">
                                <label>Description Produit</label>
                                <textarea name="description" cols="30" rows="5" class="form-control" readonly><?php echo htmlspecialchars($desc_db); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Image Matériel</label>
                                <div class="thumbnail">
                                    <img src="upload/<?php echo htmlspecialchars($product_img); ?>" alt="Product Image" style="max-height: 250px; width: auto; display: block; margin: 0 auto;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success btn-lg" name="update_product">
                        <i class="fa fa-upload"></i> Enregistrer Réception & Mettre à Jour le Stock
                    </button>
                    <a href="product.php" class="btn btn-warning btn-lg pull-right">
                        <i class="fa fa-arrow-circle-left"></i> Annuler et Retourner
                    </a>
                </div>
            </form>
        </div>


    </section>
</div>

<script>
    /**
     * Confirme la quantité de stock avant la soumission du formulaire.
     * @returns {boolean} True si l'utilisateur confirme, false sinon.
     */
    function confirmAddition() {
        // Récupère la valeur du champ de quantité reçu
        const quantityInput = document.getElementById('received_quantity');
        const quantity = quantityInput ? quantityInput.value : 0;

        if (!quantity || isNaN(quantity) || parseInt(quantity) < 1) {
            alert("Veuillez entrer une quantité valide à ajouter (minimum 1).");
            return false;
        }

        const confirmationMessage = `Êtes-vous sûr de vouloir enregistrer cette réception de **${quantity}** produit(s) et mettre à jour le stock principal?`;

        return confirm(confirmationMessage);
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>