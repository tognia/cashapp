<?php
// PHP Code: Traitement du Transfert de Stock Entrepôt vers Boutique

// 1. Better error handling and session check
include_once 'misc/plugin.php';
include_once 'db/connect_db.php';

// Check user role and redirect (assuming Responsable/Admin are the ones allowed to transfer stock)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable" && $_SESSION['role'] !== "storekeeper")) {
    header('Location: index.php');
    exit();
}

$message = ''; // Variable pour les messages d'erreur/succès

// 2. Initial data fetching from URL ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: product.php');
    exit();
}

try {
    // --- 2a. Fetch product data for the shop item (tbl_shop_item) ---
    $select_shop_item = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE product_id = :id");
    $select_shop_item->bindParam(':id', $id, PDO::PARAM_INT);
    $select_shop_item->execute();
    $shop_item = $select_shop_item->fetch(PDO::FETCH_ASSOC);

    if (!$shop_item) {
        header('Location: product.php');
        exit();
    }

    // Assign variables for current product (data needed for the form and shipment insertion)
    $id_db = $shop_item['product_id'];
    $productCode_db = $shop_item['product_code'];
    $shopCode_db = $shop_item['shop_code']; // Code de l'agence/boutique de destination
    $productName_db = $shop_item['product_name'];
    $productSku_db = $shop_item['product_sku'];
    $category_db = $shop_item['product_category'];
    $productBrand_db = $shop_item['product_brand'];
    $purchase_db = $shop_item['purchase_price'];
    $sell_db = $shop_item['sell_price'];
    $min_db = $shop_item['min_price'];
    $discount_db = $shop_item['discount'];
    $stock_db = $shop_item['stock']; // Current shop stock
    $min_stock_db = $shop_item['min_stock'];
    $satuan_db = $shop_item['product_satuan'];
    $supplier_db = $shop_item['supplier'];
    $desc_db = $shop_item['description'];
    $product_img = $shop_item['img'];
    // $stock_db already assigned above

    // --- 2b. Fetch product data from main product table (for main stock) ---
    $select_product = $pdo->prepare("SELECT stock FROM tbl_product WHERE product_code = :product_code");
    $select_product->bindParam(':product_code', $productCode_db, PDO::PARAM_STR);
    $select_product->execute();
    $product_main = $select_product->fetch(PDO::FETCH_ASSOC);

    $stock_db1 = $product_main['stock'] ?? 0; // Main warehouse stock (Default to 0)

    // Get the current logged-in user's ID
    $current_user_id = $_SESSION['user_id'] ?? null;
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $message = '<div class="alert alert-danger">Une erreur inattendue est survenue lors de la récupération des données.</div>';
}

// 3. Form submission handling (Transfer Stock)
if (isset($_POST['update_product'])) {

    // --- 3.1. Sanitize and validate ALL input ---
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $stock_to_ship = filter_input(INPUT_POST, 'stock_to_ship', FILTER_SANITIZE_NUMBER_INT);

    // Get necessary product details from DB variables (already fetched)
    $product_code_ship = $productCode_db;
    $product_name_ship = $productName_db;
    $product_sku_ship = $productSku_db;
    $shop_code_ship = $shopCode_db;

    // NEW SHIPMENT FIELDS
    $shipment_date = filter_input(INPUT_POST, 'shipment_date', FILTER_SANITIZE_STRING);
    $delivery_status = 'Delivered'; // Fixed value based on form logic
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    // Initial validation
    if (!$product_id || !is_numeric($product_id) || !is_numeric($stock_to_ship) || $stock_to_ship < 1) {
        $message = '<div class="alert alert-danger">Identifiant de produit ou quantité invalide (minimum 1).</div>';
    } elseif (!$shipment_date) {
        $message = '<div class="alert alert-danger">La date d\'expédition est requise.</div>';
    } elseif (!$current_user_id) {
        $message = '<div class="alert alert-danger">Erreur: Identifiant utilisateur non disponible.</div>';
    } else {

        $_SESSION['stock_to_ship'] = $stock_to_ship;
        // Recalculate stock values
        $new_shop_stock = $stock_db + $stock_to_ship;
        $new_main_stock = $stock_db1 - $stock_to_ship;

        // Final stock check
        if ($new_main_stock < 0) {
            $message = '<div class="alert alert-danger">Stock insuffisant dans l\'entrepôt principal. Disponible : ' . $stock_db1 . '</div>';
        } else {
            try {

                // Transactional updates for data consistency
                $pdo->beginTransaction();

                // 3a. INSERT record into tbl_product_shipment - MISE À JOUR ICI
                $insert_shipment = $pdo->prepare("
                    INSERT INTO tbl_product_shipment (
                        shipment_date, 
                        product_id, 
                        shipped_quantity, 
                        code_agence, 
                        user_id, 
                        delivery_status, 
                        notes,
                        product_code,       -- NOUVEAU
                        product_sku,        -- NOUVEAU
                        product_name        -- NOUVEAU
                    )
                    VALUES (
                        :date, 
                        :product_id, 
                        :quantity, 
                        :code_agence, 
                        :user_id, 
                        :status, 
                        :notes,
                        :product_code,      -- NOUVEAU
                        :product_sku,       -- NOUVEAU
                        :product_name       -- NOUVEAU
                    )
                ");

                $insert_shipment->bindParam(':date', $shipment_date);
                $insert_shipment->bindParam(':product_id', $id_db, PDO::PARAM_INT);
                $insert_shipment->bindParam(':quantity', $stock_to_ship, PDO::PARAM_INT);
                $insert_shipment->bindParam(':code_agence', $shop_code_ship, PDO::PARAM_STR); // Corrigé
                $insert_shipment->bindParam(':user_id', $current_user_id, PDO::PARAM_INT); // Corrigé
                // Status is 'Delivered' as per the requirement for immediate update
                $insert_shipment->bindParam(':status', $delivery_status);
                $insert_shipment->bindParam(':notes', $notes);
                // Liaison des nouvelles variables
                $insert_shipment->bindParam(':product_code', $product_code_ship);
                $insert_shipment->bindParam(':product_sku', $product_sku_ship);
                $insert_shipment->bindParam(':product_name', $product_name_ship);

                if ($insert_shipment->execute()) {

                    // 3b. Update stock in tbl_product (main warehouse) - DEDUCTION
                    $update_main = $pdo->prepare("UPDATE tbl_product SET stock = :stock WHERE product_code = :product_code");
                    $update_main->bindParam(':stock', $new_main_stock, PDO::PARAM_INT);
                    $update_main->bindParam(':product_code', $productCode_db, PDO::PARAM_STR);
                    $update_main->execute();

                    // 3c. Update stock in tbl_shop_item - ADDITION
                    // $update_shop = $pdo->prepare("UPDATE tbl_shop_item SET stock = :stock WHERE product_id = :id");
                    // $update_shop->bindParam(':stock', $new_shop_stock, PDO::PARAM_INT);
                    // $update_shop->bindParam(':id', $product_id, PDO::PARAM_INT);
                    // $update_shop->execute();

                    $pdo->commit();

                    // Redirect on success
                    header('Location: view_product_shop.php?id=' . urlencode($product_id));
                    exit();
                } else {
                    $pdo->rollBack();
                    $message = '<div class="alert alert-danger">Erreur lors de l\'enregistrement de l\'expédition.</div>';
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Transaction Error: " . $e->getMessage());
                $message = '<div class="alert alert-danger">Une erreur base de données est survenue. L\'opération a été annulée.</div>';
            }
        }
    }
}

if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
} else {
    include_once 'inc/header_all_operator.php';
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-truck"></i> Transfert de Stock Entrepôt vers Boutique (<?php echo htmlspecialchars($shopCode_db); ?>)
        </h1>
    </section>

    <section class="content container-fluid">

        <?php if (isset($message)) {
            echo $message;
        } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Transférer Stock pour **<?php echo htmlspecialchars($productName_db); ?>**</h3>
            </div>

            <form action="" method="POST" id="productForm" onsubmit="return confirmTransfer();"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <h4 class="text-primary">Informations Produit</h4>
                            <hr style="margin-top: 5px;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id_db); ?>">
                            <div class="form-group">
                                <label>Boutique</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($shopCode_db); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Code Produit</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($productCode_db); ?>" readonly>
                            </div>
                            <h4 class="text-primary" style="margin-top: 20px;">Stock Global</h4>
                            <hr style="margin-top: 5px;">
                            <div class="form-group">
                                <label>Stock Actuel en Boutique</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($stock_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Stock Principal (Entrepôt)</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($stock_db1); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h4 class="text-primary">Quantité à Transférer</h4>
                            <hr style="margin-top: 5px;">

                            <div class="form-group bg-warning" style="padding: 10px; border-radius: 4px;">
                                <label for="stock_to_ship" style="color: #66512c;">Quantité à Expédier <span class="text-danger">*</span></label>
                                <input type="text" id="stock_to_ship" min="1" step="1"
                                    max="<?php echo htmlspecialchars($stock_db1); ?>"
                                    class="form-control input-lg" name="stock_to_ship" required
                                    placeholder="Qté à expédier (Max: <?php echo htmlspecialchars($stock_db1); ?>)"
                                    style="font-size: 1.5em;">
                                <span class="help-block">Cette quantité sera déduite de l'entrepôt principal.</span>
                            </div>

                            <h4 class="text-primary" style="margin-top: 20px;">Détails de l'Expédition</h4>
                            <hr style="margin-top: 5px;">

                            <div class="form-group">
                                <label for="shipment_date">Date d'Expédition <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="shipment_date" id="shipment_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="delivery_status">Statut de Livraison</label>
                                <input type="text" class="form-control" name="delivery_status" id="delivery_status" value="Delivered" readonly>
                                <span class="help-block">Statut par défaut (mis à jour immédiatement).</span>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes d'Expédition (Réf. Bon de sortie, etc.)</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Ajouter un commentaire ou une référence..."></textarea>
                            </div>

                        </div>


                        <div class="col-md-4">
                            <h4 class="text-primary">Visuel et Description</h4>
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
                        <i class="fa fa-share-square-o"></i> Confirmer Transfert & Mettre à Jour les Stocks
                    </button>
                    <a href="product_shop_item.php" class="btn btn-warning btn-lg pull-right">
                        <i class="fa fa-arrow-circle-left"></i> Annuler et Retourner
                    </a>
                </div>
            </form>
        </div>


    </section>
</div>

<script>
    /**
     * Confirms the stock transfer quantity with the user.
     * @returns {boolean} True if the user confirms, false otherwise.
     */
    function confirmTransfer() {
        const quantityInput = document.getElementById('stock_to_ship');
        const quantity = quantityInput ? quantityInput.value : 0;
        const max = quantityInput ? quantityInput.max : Infinity; // Récupère la limite max

        if (!quantity || isNaN(quantity) || parseInt(quantity) < 1) {
            alert("Veuillez entrer une quantité valide à expédier (minimum 1).");
            return false;
        }

        if (parseInt(quantity) > parseInt(max)) {
            alert(`Erreur : La quantité à transférer (${quantity}) dépasse le stock disponible dans l'entrepôt (${max}).`);
            return false;
        }

        const confirmationMessage = `CONFIRMER LE TRANSFERT :\n\nÊtes-vous sûr de vouloir transférer **${quantity}** produit(s) de l'entrepôt principal à cette boutique ?\n\n(L'expédition sera enregistrée et les stocks mis à jour.)`;

        return confirm(confirmationMessage);
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>