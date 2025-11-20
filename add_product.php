<?php
include_once 'db/connect_db.php';
if ($_SESSION['user_name'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

if (isset($_POST['add_product'])) {
    
    include_once 'category_new.php';
    
    $code = str_replace(' ', '', $_POST['product_code']);
    $sku = str_replace(' ', '', $_POST['product_sku']);
    $product = $_POST['product_name'];
    $category = ($_POST['category_new']!="")?$_POST['category_new']:$_POST['category'];
    $brand = $_POST['product_brand'];
    $purchase = $_POST['purchase_price'];
    $sell = $_POST['sell_price'];
    $min = $_POST['min_price'];
    $discount = $_POST['discount'];
    $stock = $_POST['stock'];
    $min_stock = $_POST['min_stock'];
    $satuan = $_POST['satuan'];
    if($_POST['supplier']!=""){
        $supplier = $_POST['supplier'];
    }else {
        $supplier = $brand ;
    }
      
    $desc = $_POST['description'];
    $place_in_store = $_POST['place_in_store'];
    $place_in_storeroom = $_POST['place_in_storeroom'];

    if (isset($_POST['product_code'])) {
        $select = $pdo->prepare("SELECT product_code FROM tbl_product WHERE product_code='$code'");
        $select->execute();

        if ($select->rowCount() > 0) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Produit existant | Erreur", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        }
        //elseif (strlen($code)>15 || strlen($code)<15)
        elseif (strlen($code) > 50) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Le code doit avoir au plus 50 caracteres", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
            $img = $_FILES['product_img']['name'];
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

                            $insert = $pdo->prepare("INSERT INTO tbl_product
                                        (product_code,product_sku,product_name,product_category,
                                        product_brand,purchase_price,
                                        sell_price,min_price,discount,stock,min_stock,product_satuan,supplier,
                                        description,img,place_in_storeroom, place_in_store)
                            values(:product_code,:product_sku,:product_name,:product_category,:product_brand,:purchase_price,:sell_price,
                            :min_price,:discount,:stock,:min_stock,:satuan,:supplier,
                            :desc1,:img,:place_in_storeroom, :place_in_store)");

                            $insert->bindParam(':product_code', $code);
                            $insert->bindParam(':product_sku', $sku);
                            $insert->bindParam(':product_name', $product);
                            $insert->bindParam(':product_category', $category);
                            $insert->bindParam(':product_brand', $brand);
                            $insert->bindParam(':purchase_price', $purchase);
                            $insert->bindParam(':sell_price', $sell);
                            $insert->bindParam(':min_price', $min);
                            $insert->bindParam(':discount', $discount);
                            $insert->bindParam(':stock', $stock);
                            $insert->bindParam(':min_stock', $min_stock);
                            $insert->bindParam(':satuan', $satuan);
                            $insert->bindParam(':supplier', $supplier);
                            $insert->bindParam(':desc1', $desc);
                            $insert->bindParam(':img', $product_img);
                            $insert->bindParam(':place_in_store', $place_in_store);
                            $insert->bindParam(':place_in_storeroom', $place_in_storeroom);

                            if ($insert->execute()) {

                                // ENREGISTREMENT DANS TBL_SHOP_ITEM 
                                $select1 = $pdo->prepare("SELECT * FROM agence");
                                $select1->execute();
                                while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                                    //extract($row)

                                    $shop = $row['code_agence'];
                                    $stock = 0;
                                    $min_stock = 0;
                                    $insert_item = $pdo->prepare("INSERT INTO tbl_shop_item(shop_code,product_code,product_sku,product_name,
                                product_category,product_brand,purchase_price,sell_price,min_price,discount,stock,
                                min_stock,product_satuan,supplier,description,place_in_store,img)
                            values(:shop,:product_code,:product_sku,:product_name,:product_category,
                            :product_brand,:purchase_price,:sell_price,:min_price,:discount,:stock,
                            :min_stock,:satuan,:supplier,:desc1,:place_in_store,:img)");

                                    $insert_item->bindParam(':shop', $shop);
                                    $insert_item->bindParam(':product_code', $code);
                                    $insert_item->bindParam(':product_sku', $sku);
                                    $insert_item->bindParam(':product_name', $product);
                                    $insert_item->bindParam(':product_category', $category);
                                    $insert_item->bindParam(':product_brand', $brand);
                                    $insert_item->bindParam(':purchase_price', $purchase);
                                    $insert_item->bindParam(':sell_price', $sell);
                                    $insert_item->bindParam(':min_price', $min);
                                    $insert_item->bindParam(':discount', $discount);
                                    $insert_item->bindParam(':stock', $stock);
                                    $insert_item->bindParam(':min_stock', $min_stock);
                                    $insert_item->bindParam(':satuan', $satuan);
                                    $insert_item->bindParam(':supplier', $supplier);
                                    $insert_item->bindParam(':desc1', $desc);
                                    $insert_item->bindParam(':place_in_store', $place_in_store);
                                    $insert_item->bindParam(':img', $product_img);


                                    if ($insert_item->execute()) {

                                        echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Success", "Produit Ajouté dans Magasin ' . $shop . ' avec succes", "success", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                                    } else {
                                        echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Echec Ajout Produit dans Magasin "' . $shop . ', "error", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                                    }
                                } // FIN ENREGISTREMENT DANS TBL_SHOP_ITEM  



                                ///////////////////////////////////////////////////


                                echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Success", "Produit enregistre avec succes", "success", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                            } else {
                                echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Erreur Enregistrement", "error", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                            }
                        } else {
                            echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Il y a une erreur", "error", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
                        }
                    }
                }
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
        }
    }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Produit
        </h1>
        <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Entrez Informations nouveau produit</h3>
            </div>
            <form action="" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Code produit</label><br>
                            <span class="text-muted">*Assurez-vous que le code produit correspond</span>
                            <input type="text" class="form-control" name="product_code" required>
                        </div>
                        <div class="form-group">
                            <label for="">SKU</label><br>
                            <span class="text-muted">*Assurez-vous que le code produit correspond</span>
                            <input type="text" class="form-control" name="product_sku">
                        </div>
                        <div class="form-group">
                            <label for="">Libelle Produit</label>
                            <input type="text" class="form-control" name="product_name">
                        </div>
                        <div class="form-group">
                            <label for="">Categorie</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_category where cat_level = 3");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option><?php echo $row['cat_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="loadCategoryModal">CREER NOUVELLE CATEGORIE</button>
                            <div id="categorySection" style="display: none;">
                                <br />
                                <label for="category">Libelle</label>
                                <input type="text" class="form-control" name="category_new" placeholder="Enter Category">
                            </div>
                            <div id="categoryModalContainer"></div><br /><br />
                        </div>

                        <div class="form-group">
                            <label for="">Emplacement au Dépôt</label>
                            <input type="text" class="form-control" name="place_in_storeroom" cols="30" rows="10" value="<?php 
                            if(isset($place_in_storeroom))
                            echo $place_in_storeroom; 
                            else echo "";
                            ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Emplacement au Magasin</label>
                            <input type="text" class="form-control" name="place_in_store" cols="30" rows="10" value="<?php 
                            if(isset($place_in_store))
                            echo $place_in_store; 
                            else echo "";
                            ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="">Marque</label><br>
                            <span class="text-muted"></span>
                            <input type="text" class="form-control" name="product_brand">
                        </div>
                        <div class="form-group">
                            <label for="">Prix Achat</label>
                            <input type="number" min="10" class="form-control" name="purchase_price" required>
                        </div>
                        <div class="form-group">
                            <label for="">Prix Vente</label>
                            <input type="number" class="form-control" name="sell_price" required>
                        </div>
                        <div class="form-group">
                            <label for="">Prix Min</label>
                            <input type="number" class="form-control" name="min_price" required>
                        </div>
                        <div class="form-group">
                            <label for="">Discount</label>
                            <input type="number" class="form-control" name="discount" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Stock</label><br>
                            <span class="text-muted">*Unite selon produit</span>
                            <input type="number" min="1" step="1" class="form-control" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Stock minimal</label><br>
                            <input type="number" min="1" step="1" class="form-control" name="min_stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Fournisseur</label>
                            <select class="form-control" name="supplier">
                            <option></option>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM supliers");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option><?php echo $row['suplier_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Unite</label>
                            <select class="form-control" name="satuan" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_satuan");
                                $select->execute();
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option><?php echo $row['nm_satuan']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Description Produit</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Image Produit</label><br>
                            <br>
                            <input type="file" class="input-group" name="product_img" onchange="readURL(this);" required> <br>
                            <img id="img_preview" src="upload/60793c4660233.png<?php //echo $row->img
                                                                                ?>" alt="Preview" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="add_product">Enregistrer Produit</button>
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
<script type="text/javascript">
    document.getElementById('loadCategoryModal').addEventListener('click', function () {
        document.getElementById('categorySection').style.display = 'block';
    });
</script>


<?php
include_once 'inc/footer_all.php';
?>