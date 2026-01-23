<?php
include_once 'misc/plugin.php';
include_once 'db/connect_db.php';
if ($_SESSION['role'] !== "Admin") {
    header('location:index.php');
}

if ($id = $_GET['id']) {
    $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $update;
    $productCode_db = $row['product_code'];
    $productSku_db = $row['product_sku'];
    $productName_db = $row['product_name'];
    $category_db = $row['product_category'];
    $productBrand_db = $row['product_brand'];
    $purchase_db = $row['purchase_price'];
    $sell_db = $row['sell_price'];
    $min_db = $row['min_price'];
    $discount_db = $row['discount'];
    $stock_db = $row['stock'];
    $min_stock_db = $row['min_stock'];
    $satuan_db = $row['product_satuan'];
    $supplier_db = $row['supplier'];
    $desc_db = $row['description'];
    $product_img = $row['img'];
    $place_in_store = $row['place_in_store'];
    $place_in_storeroom = $row['place_in_storeroom'];
} else {
    header('location:product.php');
}

if (isset($_POST['update_product'])) {
    $code_req = $_POST['product_code'];
    $product_sku_req = $_POST['product_sku'];
    $product_req = $_POST['product_name'];
    $category_req = $_POST['category'];
    $product_brand_req = $_POST['product_brand'];
    $purchase_req = $_POST['purchase_price'];
    $sell_req = $_POST['sell_price'];
    $min_req = $_POST['min_price'];
    $discount_req = $_POST['discount'];
    $stock_req = $_POST['stock'];
    $min_stock_req = $_POST['min_stock'];
    $satuan_req = $_POST['satuan'];
    $supplier_req = $_POST['supplier'];
    $desc_req = $_POST['description'];
    $place_in_store = $_POST['place_in_store'];
    $place_in_storeroom = $_POST['place_in_storeroom'];
    $img = $_FILES['product_img']['name'];
    if (!empty($img)) {
        $img_tmp = $_FILES['product_img']['tmp_name'];
        $img_size = $_FILES['product_img']['size'];
        $img_ext = explode('.', $img);
        $img_ext = strtolower(end($img_ext));

        $img_new = uniqid() . '.' . $img_ext;
        $store = "upload/" . $img_new;

        if ($img_ext == 'jpg' || $img_ext == 'jpeg' || $img_ext == 'png' || $img_ext == 'gif') {
            if ($img_size >= 1000000) {
                $error = '<script type="text/javascript">
                                jQuery(function validation(){
                                swal("Error", "Taille Max du fichie 1 Mo", "error", {
                                button: "Continue",
                                    });
                                });
                                </script>';
                echo $error;
            } else {
                if (move_uploaded_file($img_tmp, $store)) {
                    $product_img = $img_new;
                    if (!isset($error)) {
                        $update = $pdo->prepare("UPDATE tbl_product SET product_code=:product_code,product_sku=:product_sku,product_name=:product_name,
                                product_category=:product_category, product_brand=:product_brand, purchase_price=:purchase_price, sell_price=:sell_price,min_price=:min_price,discount=:discount,
                                stock=:stock,min_stock=:min_stock, product_satuan=:product_satuan , supplier=:supplier, place_in_store=:place_in_store, place_in_storeroom=:place_in_storeroom, description=:description, img=:img WHERE product_id=:id");

                        $update->bindParam('product_code', $code_req);
                        $update->bindParam('product_sku', $product_sku_req);
                        $update->bindParam('product_name', $product_req);
                        $update->bindParam('product_category', $category_req);
                        $update->bindParam('product_brand', $product_brand_req);
                        $update->bindParam('product_code', $code_req);
                        $update->bindParam('purchase_price', $purchase_req);
                        $update->bindParam('sell_price', $sell_req);
                        $update->bindParam('min_price', $min_req);
                        $update->bindParam('discount', $discount_req);
                        $update->bindParam('stock', $stock_req);
                        $update->bindParam('min_stock', $min_stock_req);
                        $update->bindParam('product_satuan', $satuan_req);
                        $update->bindParam('supplier', $supplier_req);
                        $update->bindParam('description', $desc_req);
                        $update->bindParam('place_in_store', $place_in_store);
                        $update->bindParam('place_in_storeroom', $place_in_storeroom);
                        $update->bindParam('img',  $product_img);
                        $update->bindParam(':id',  $id);

                        if ($update->execute()) {
                            ////////////////////////////////////   UPDATE TABLE OF PRODUCTS IN DIFFERENTS SHOPS ///////////////////////////////

                            $select1 = $pdo->prepare("SELECT * FROM agence");
                            $select1->execute();
                            while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                                $product_code_clean = str_replace(' ', '', $code_req);
                                $shop = $row['code_agence'];
                                // echo  $shop." ";
                                // echo $code_req.$product_req;
                                $update1 = $pdo->prepare("UPDATE tbl_shop_item SET product_code=:product_code, product_sku=:product_sku,product_name=:product_name,
                                            product_category=:product_category, product_brand=:product_brand, purchase_price=:purchase_price, 
                                            sell_price=:sell_price, min_price=:min_price, discount=:discount,
                                            min_stock=:min_stock, product_satuan=:product_satuan,
                                            supplier=:supplier,description=:description, img=:img WHERE REPLACE(product_code, ' ', '') = :product_code");
                                $update1->bindParam('product_code', $product_code_clean);
                                $update1->bindParam('product_sku', $product_sku_req);
                                $update1->bindParam('product_name', $product_req);
                                $update1->bindParam('product_category', $category_req);
                                $update1->bindParam('product_brand', $product_brand_req);
                                $update1->bindParam('purchase_price', $purchase_req);
                                $update1->bindParam('sell_price', $sell_req);
                                $update1->bindParam('min_price', $min_req);
                                $update1->bindParam('discount', $discount_req);
                                $update1->bindParam('min_stock', $min_stock_req);
                                $update1->bindParam('product_satuan', $satuan_req);
                                $update1->bindParam('supplier', $supplier_req);
                                $update1->bindParam('description', $desc_req);
                                $update1->bindParam('img',  $product_img);

                                if ($update1->execute()) {
                                    // echo "Peace1";
                                } else {
                                    echo "War1";
                                }
                            }
                        } else {
                            echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Erreur En registrement", "error", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                        }
                    } else {
                        echo 'Erreur Upload File';
                    }
                }
            } ///////// *********




        } else {
            $error = '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Error", "Format image recquis : jpg, jpeg, png, gif", "error", {
                    button: "Continue",
                        });
                    });
                    </script>';
            echo $error;
        }
    } else {
        $update = $pdo->prepare("UPDATE tbl_product SET product_code=:product_code,product_sku=:product_sku,product_name=:product_name,
                product_category=:product_category, product_brand=:product_brand, purchase_price=:purchase_price, sell_price=:sell_price, min_price=:min_price, discount=:discount,
                stock=:stock,min_stock=:min_stock, product_satuan=:product_satuan, 
                supplier=:supplier, place_in_store=:place_in_store, place_in_storeroom=:place_in_storeroom, 
                description=:description WHERE product_id=:id");

        $update->bindParam('product_code', $code_req);
        $update->bindParam('product_sku', $product_sku_req);
        $update->bindParam('product_name', $product_req);
        $update->bindParam('product_category', $category_req);
        $update->bindParam('product_brand', $product_brand_req);
        $update->bindParam('purchase_price', $purchase_req);
        $update->bindParam('sell_price', $sell_req);
        $update->bindParam('min_price', $min_req);
        $update->bindParam('discount', $discount_req);
        $update->bindParam('stock', $stock_req);
        $update->bindParam('min_stock', $min_stock_req);
        $update->bindParam('product_satuan', $satuan_req);
        $update->bindParam('supplier', $supplier_req);
        $update->bindParam('description', $desc_req);
        $update->bindParam('place_in_store', $place_in_store);
        $update->bindParam('place_in_storeroom', $place_in_storeroom);
        $update->bindParam(':id',  $id);


        if ($update->execute()) {

            ////////////////////////////////////   UPDATE TABLE OF PRODUCTS IN DIFFERENTS SHOPS ///////////////////////////////
            // $product_code_clean = preg_replace('/[^A-Za-z0-9]/', '', $code_req);
            // $product_code_clean = substr($code_req,0);
            $product_code_clean = str_replace(' ', '', $code_req);
            $select1 = $pdo->prepare("SELECT * FROM agence");
            $select1->execute();
            while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                $shop = $row['code_agence'];
                $update2 = $pdo->prepare("UPDATE tbl_shop_item SET product_code=:product_code, product_sku=:product_sku,product_name=:product_name,
                            product_category=:product_category, product_brand=:product_brand, purchase_price=:purchase_price, 
                            sell_price=:sell_price, min_price=:min_price, discount=:discount,
                            min_stock=:min_stock, product_satuan=:product_satuan,
                            supplier=:supplier, description=:description 
                            WHERE REPLACE(product_code, ' ', '') = :product_code");

                $update2->bindParam('product_code', $product_code_clean);
                $update2->bindParam('product_sku', $product_sku_req);
                $update2->bindParam('product_name', $product_req);
                $update2->bindParam('product_category', $category_req);
                $update2->bindParam('product_brand', $product_brand_req);
                $update2->bindParam('purchase_price', $purchase_req);
                $update2->bindParam('sell_price', $sell_req);
                $update2->bindParam('min_price', $min_req);
                $update2->bindParam('discount', $discount_req);
                $update2->bindParam('min_stock', $min_stock_req);
                $update2->bindParam('product_satuan', $satuan_req);
                $update2->bindParam('supplier', $supplier_req);
                $update2->bindParam('description', $desc_req);

                if ($update2->execute()) {
                    // echo "Peace";
                } else {
                    echo "Error";
                }
            }
            // echo "ZONE";
            header('location:view_product.php?id=' . urlencode($id));
        } else {
            echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Error", "Erreur En registrement", "error", {
                        button: "Continue",
                            });
                        });
                        </script>';
        }
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
                <h3 class="box-title">Edit Product</h3>
            </div>
            <form action="" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Code Produit</label>
                            <input type="text" class="form-control" name="product_code" value="<?php echo $productCode_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">SKU</label>
                            <input type="text" class="form-control" name="product_sku" value="<?php echo $productSku_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Libelle Produit</label>
                            <input type="text" class="form-control" name="product_name" value="<?php echo $productName_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Categorie</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_category where cat_level = 3");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['cat_name'] == $category_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['cat_name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Emplacement au Magasin</label>
                            <input type="text" class="form-control" name="place_in_store" cols="30" rows="10" value="<?php echo $place_in_store; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Emplacement au Dépôt</label>
                            <input type="text" class="form-control" name="place_in_storeroom" cols="30" rows="10" value="<?php echo $place_in_storeroom; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Marque</label>
                            <input type="text" class="form-control" name="product_brand" value="<?php echo $productBrand_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Prix Achat</label>
                            <input type="number" class="form-control" name="purchase_price" value="<?php echo $purchase_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Prix de vente</label>
                            <input type="number" class="form-control" name="sell_price" value="<?php echo $sell_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Prix Min</label>
                            <input type="number" class="form-control" name="min_price" value="<?php echo $min_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Discount</label>
                            <input type="number" class="form-control" name="discount" value="<?php echo $discount_db; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Stock</label>
                            <input type="number" step="1" class="form-control" name="stock" value="<?php echo $stock_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Stock Minimal</label>
                            <input type="number" min="1" step="1" class="form-control" name="min_stock" value="<?php echo $min_stock_db; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Unite</label>
                            <select class="form-control" name="satuan" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_satuan");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['nm_satuan'] == $satuan_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['nm_satuan']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Fournisseur</label>
                            <select class="form-control" name="supplier" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM supliers");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['suplier_name'] == $supplier_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['suplier_name']; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Description Produit</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control" required><?php echo $desc_db; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Image Materiel</label>
                            <input type="file" class="input-group" name="product_img" onchange="readURL(this);"> <br>
                            <img src="upload/<?php echo $product_img ?>" alt="Preview" class="img-responsive" />

                            <!-- <img id="img_preview" src="upload/60793c4660233.png<?php //echo $row->img
                                                                                    ?>" alt="Preview" class="img-responsive" /> -->
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="update_product">Enregistrer</button>
                    <a href="product.php" class="btn btn-warning">Retour</a>
                </div>
            </form>

        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img_preview').attr('src', e.target.result)
                    .width(250)
                    .height(200);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>