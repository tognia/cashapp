<?php
// PHP Code Refactoring and Improvement

// 1. Better error handling and session check
include_once 'misc/plugin.php';
include_once 'db/connect_db.php';

// Check user role and redirect
if ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable") {
    header('Location: index.php');
    exit(); // Always use exit() after header redirect
}

// 2. Initial data fetching from URL ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: product.php');
    exit();
}

try {
    // Fetch product data for the shop item
    $select_shop_item = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE product_id = :id");
    $select_shop_item->bindParam(':id', $id, PDO::PARAM_INT);
    $select_shop_item->execute();
    $shop_item = $select_shop_item->fetch(PDO::FETCH_ASSOC);

    if (!$shop_item) {
        // Product not found in shop, redirect
        header('Location: product.php');
        exit();
    }

    // Assign variables for current product (using shop_item array directly is cleaner, but keeping your original variables for context)
    $id_db = $shop_item['product_id'];
    $productCode_db = $shop_item['product_code'];
    $shopCode_db = $shop_item['shop_code'];
    $productSku_db = $shop_item['product_sku'];
    $productName_db = $shop_item['product_name'];
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

    // Fetch product data from main product table (for main stock)
    $select_product = $pdo->prepare("SELECT stock FROM tbl_product WHERE product_code = :product_code");
    $select_product->bindParam(':product_code', $productCode_db, PDO::PARAM_STR);
    $select_product->execute();
    $product_main = $select_product->fetch(PDO::FETCH_ASSOC);

    if (!$product_main) {
        // Main product not found, handle error or assume 0 main stock
        $stock_db1 = 0;
    } else {
        $stock_db1 = $product_main['stock']; // Main warehouse stock
    }
} catch (PDOException $e) {
    // Log error and redirect or display a generic error message
    error_log("Database Error: " . $e->getMessage());
    echo "An unexpected error occurred.";
    exit();
}


// 3. Form submission handling
if (isset($_POST['update_product'])) {
    // Sanitize and validate input
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $stock_to_add = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT); // This is the quantity to add

    // Simple validation
    if (!$product_id || !is_numeric($product_id) || !is_numeric($stock_to_add) || $stock_to_add < 1) {
        $message = '<div class="alert alert-danger">Invalid product ID or quantity.</div>';
    } else {
        // Recalculate stock values
        $new_shop_stock = $stock_db + $stock_to_add; // Current shop stock + quantity to add
        $new_main_stock = $stock_db1 - $stock_to_add; // Current main stock - quantity removed

        // Prevent negative stock in the main warehouse
        if ($new_main_stock < 0) {
            $message = '<div class="alert alert-danger">Insufficient stock in the main warehouse. Available: ' . $stock_db1 . '</div>';
        } else {
            try {
                // Transactional updates for data consistency
                $pdo->beginTransaction();

                // 3a. Update stock in tbl_shop_item
                $update_shop = $pdo->prepare("UPDATE tbl_shop_item SET stock = :stock WHERE product_id = :id");
                $update_shop->bindParam(':stock', $new_shop_stock, PDO::PARAM_INT);
                $update_shop->bindParam(':id', $product_id, PDO::PARAM_INT);
                $update_shop->execute();

                // 3b. Update stock in tbl_product (main warehouse)
                $update_main = $pdo->prepare("UPDATE tbl_product SET stock = :stock WHERE product_code = :product_code");
                $update_main->bindParam(':stock', $new_main_stock, PDO::PARAM_INT);
                $update_main->bindParam(':product_code', $productCode_db, PDO::PARAM_STR); // Use the fetched product code
                $update_main->execute();

                $pdo->commit();

                // Redirect on success
                header('Location: view_product_shop.php?id=' . urlencode($product_id));
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack(); // Revert changes if anything fails
                error_log("Transaction Error: " . $e->getMessage());
                $message = '<div class="alert alert-danger">An error occurred during the stock update.</div>';
            }
        }
    }
}


// HTML output starts here
include_once 'inc/header_all.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil-square-o"></i> Modifier le Stock Boutique
        </h1>
    </section>

    <section class="content container-fluid">

        <?php if (isset($message)) {
            echo $message;
        } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ajouter du Stock pour **<?php echo htmlspecialchars($productName_db); ?>**</h3>
            </div>
            <form action="" method="POST" id="productForm" onsubmit="return confirmAddition();"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <h4 class="text-primary">Informations Produit</h4>
                            <hr style="margin-top: 5px;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id_db); ?>">

                            <div class="form-group">
                                <label>Boutique</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($shopCode_db); ?>" readonly>
                            </div>
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
                            <h4 class="text-primary">Stock et Prix</h4>
                            <hr style="margin-top: 5px;">

                            <div class="form-group">
                                <label>Stock Actuel en Boutique</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($stock_db); ?>" readonly>
                                <span class="help-block">Stock disponible dans cette boutique.</span>
                            </div>

                            <div class="form-group bg-info" style="padding: 10px; border-radius: 4px;">
                                <label for="stock_to_add" style="color: #31708f;">Quantité à Ajouter</label>
                                <input type="number" id="stock_to_add" min="1" step="1"
                                    max="<?php echo htmlspecialchars($stock_db1); ?>"
                                    class="form-control input-lg" name="stock" required
                                    placeholder="Entrez la quantité à transférer (Max: <?php echo htmlspecialchars($stock_db1); ?>)"
                                    style="font-size: 1.5em;">
                                <span class="help-block">Max disponible au magasin principal: **<?php echo htmlspecialchars($stock_db1); ?>**</span>
                            </div>

                            <div class="form-group">
                                <label>Stock Minimal Requis</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($min_stock_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Unité de Mesure</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($satuan_db); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label>Prix Achat / Vente / Min</label>
                                <p class="form-control-static">
                                    Achat: **<?php echo htmlspecialchars($purchase_db); ?>** |
                                    Vente: **<?php echo htmlspecialchars($sell_db); ?>** |
                                    Min: **<?php echo htmlspecialchars($min_db); ?>**
                                </p>
                            </div>
                            <div class="form-group">
                                <label>Discount</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($discount_db); ?>" readonly>
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
                        <i class="fa fa-plus-circle"></i> Confirmer l'Ajout du Stock
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
     * Confirms the stock addition quantity with the user.
     * @returns {boolean} True if the user confirms, false otherwise.
     */
    function confirmAddition() {
        // Get the value from the input field with name="stock" and ID "stock_to_add"
        const quantityInput = document.getElementById('stock_to_add');
        const quantity = quantityInput ? quantityInput.value : 0;

        // Basic validation check (though HTML required/min/max should handle this)
        if (!quantity || isNaN(quantity) || quantity < 1) {
            alert("Veuillez entrer une quantité valide à ajouter (minimum 1).");
            return false;
        }

        // The confirmation message
        const confirmationMessage = `Êtes-vous sûr de vouloir ajouter **${quantity}** produit(s) à la boutique?`;

        // Show the confirmation dialog
        return confirm(confirmationMessage);
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>