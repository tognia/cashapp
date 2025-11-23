<?php
// PHP Code Refactoring and Improvement
include_once 'misc/plugin.php';
include_once 'db/connect_db.php';

// Check user role and redirect
if ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable" && $_SESSION['role'] !== "storekeeper") {
    header('Location: index.php');
    exit(); // Always use exit() after header redirect
}

// 1. Initial data fetching from URL ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: product.php');
    exit();
}

// Message variable to store success/error messages
$message = '';

try {
    // Fetch product data from the main product table (tbl_product)
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id = :id");
    $select->bindParam(':id', $id, PDO::PARAM_INT);
    $select->execute();
    $product_data = $select->fetch(PDO::FETCH_ASSOC);

    if (!$product_data) {
        // Product not found, redirect
        header('Location: product.php');
        exit();
    }

    // Assign variables from fetched data
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
    $stock_db = $product_data['stock']; // Current main warehouse stock
    $min_stock_db = $product_data['min_stock'];
    $satuan_db = $product_data['product_satuan'];
    $supplier_db = $product_data['supplier'];
    $desc_db = $product_data['description'];
    $product_img = $product_data['img'];

    // Note: The second query (`$select1`) is redundant since you are fetching from tbl_product and using $productCode_db which is unique within this context. I've removed it for cleaner code, as $stock_db already holds the current stock.
    $stock_db_current = $stock_db; // Use a clearer name for the current stock before addition

} catch (PDOException $e) {
    // Log error and display a generic message
    error_log("Database Error: " . $e->getMessage());
    $message = '<div class="alert alert-danger">An unexpected error occurred while fetching product data.</div>';
}


// 2. Form submission handling (Adding Stock)
if (isset($_POST['update_product'])) {
    // Sanitize and validate input
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $stock_to_add = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT); // This is the quantity to add

    // Simple validation
    if (!$product_id || !is_numeric($product_id) || !is_numeric($stock_to_add) || $stock_to_add < 1) {
        $message = '<div class="alert alert-danger">Identifiant de produit ou quantité invalide.</div>';
    } else {
        try {
            // Calculate the new stock: current stock + quantity to add
            $new_total_stock = $stock_db_current + $stock_to_add;

            // Update stock in tbl_product (main warehouse)
            $update = $pdo->prepare("UPDATE tbl_product SET stock = :stock WHERE product_id = :id");

            $update->bindParam(':stock', $new_total_stock, PDO::PARAM_INT);
            $update->bindParam(':id', $product_id, PDO::PARAM_INT);

            if ($update->execute()) {
                // Redirect on success
                header('Location: view_product.php?id=' . urlencode($product_id));
                exit();
            } else {
                $message = '<div class="alert alert-danger">Erreur lors de la mise à jour du stock.</div>';
            }
        } catch (PDOException $e) {
            error_log("Update Error: " . $e->getMessage());
            $message = '<div class="alert alert-danger">Une erreur base de données est survenue.</div>';
        }
    }
}


// HTML output starts here
include_once 'inc/header_all.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus-square"></i> Ajouter du Stock au Magasin Principal
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
                            <h4 class="text-success">Stock et Ajout</h4>
                            <hr style="margin-top: 5px;">

                            <div class="form-group">
                                <label>Stock Actuel (Magasin Principal)</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($stock_db_current); ?>" readonly>
                                <span class="help-block">Stock total avant cet ajout.</span>
                            </div>

                            <div class="form-group bg-success" style="padding: 10px; border-radius: 4px;">
                                <label for="stock_to_add" style="color: #0b2e13;">Quantité à Ajouter</label>
                                <input type="number" id="stock_to_add" min="1" step="1"
                                    class="form-control input-lg" name="stock" required
                                    placeholder="Entrez la quantité à ajouter"
                                    style="font-size: 1.5em;">
                                <span class="help-block">Cette quantité sera ajoutée au stock actuel.</span>
                            </div>

                            <div class="form-group">
                                <label>Stock Minimal</label>
                                <input type="number" class="form-control" value="<?php echo htmlspecialchars($min_stock_db); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Unité</label>
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
                        <i class="fa fa-upload"></i> Confirmer l'Ajout au Stock
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
     * Confirms the stock addition quantity with the user.
     * @returns {boolean} True if the user confirms, false otherwise.
     */
    function confirmAddition() {
        // Get the value from the input field with name="stock" and ID "stock_to_add"
        const quantityInput = document.getElementById('stock_to_add');
        const quantity = quantityInput ? quantityInput.value : 0;

        // Basic validation check (though HTML required/min should handle this)
        if (!quantity || isNaN(quantity) || quantity < 1) {
            alert("Veuillez entrer une quantité valide à ajouter (minimum 1).");
            return false;
        }

        // The confirmation message
        const confirmationMessage = `Êtes-vous sûr de vouloir ajouter **${quantity}** produit(s) au stock principal?`;

        // Show the confirmation dialog
        return confirm(confirmationMessage);
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>