<?php
    include_once'misc/plugin.php';
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['role']!=="Admin" && $_SESSION['role']!=="Responsable"){
    header('location:index.php');
    }

    if($id=$_GET['id']){
    $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE product_id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);


    $id_db = $row['product_id'];
    $productCode_db = $row['product_code'];
    $shopCode_db = $row['shop_code'];
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
    


    $select1 = $pdo->prepare("SELECT * FROM tbl_product WHERE product_code='$productCode_db'");
    $select1->execute();
    $row1 = $select1->fetch(PDO::FETCH_ASSOC);


    $id_db1 = $row1['product_id'];
    $productCode_db1 = $row['product_code'];
    $stock_db1 = $row1['stock'];

    }else{
    header('location:product.php');
    }

    if(isset($_POST['update_product'])){
        $id = $_POST['product_id'];
        
        $stock_req = $_POST['stock'];
        
                  $stockToRemove = $stock_req;
                

                                $stock_req = $stock_req + $stock_db;

                                echo $shopCode_db." ".$stock_req." ".$id;

                                $update = $pdo->prepare("UPDATE tbl_shop_item SET 
                                stock=:stock WHERE product_id=$id");
                                                              
                                $update->bindParam('stock', $stock_req);                               
                                
      
                                if($update->execute()){
                                    
                                $stock_mag = $stock_db1 - $stockToRemove;
                                echo $shopCode_db."blalllaal".$stock_req." ".$id;
                                $update1 = $pdo->prepare("UPDATE tbl_product SET 
                                stock=:stock WHERE product_code='$productCode_db1'");
                                                              
                                $update1->bindParam('stock', $stock_mag);

                                    if($update1->execute()){

                                    header('location:view_product_shop.php?id='.urlencode($id));
                               }
                                }else{
                                    echo 'Something is Wrong';
                                }

  
            }
    

    include_once'inc/header_all.php';
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
            <form action="" method="POST" name="form_product"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">SHOP</label>
                            <input type="text" class="form-control"
                            name="shop_code" value="<?php echo $shopCode_db; ?>" required readonly>
                            <input type="hidden" name="product_id" value="<?php echo $id_db; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Code Produit</label>
                            <input type="text" class="form-control"
                            name="product_code" value="<?php echo $productCode_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">SKU</label>
                            <input type="text" class="form-control"
                            name="product_sku" value="<?php echo $productSku_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Libelle Produit</label>
                            <input type="text" class="form-control"
                            name="product_name" value="<?php echo $productName_db; ?>" required readonly>
                        </div>
                         

                        <div class="form-group">
                            <?php //echo $id_db1.$productCode_db.$stock_db1; ?>
                            STOCK EN BOUTIQUE<input type="number" min="0" step="1"
                            class="form-control" name="" value="<?php echo $stock_db; ?>" required readonly>
                        </div>
                        <div>
                            <span class="badge badge-info">
                            QUANTITE STOCK &nbsp; A &nbsp; AJOUTER</span>
                            <input type="number" min="1" step="1"
                            class="form-control" name="stock" value="<?php //echo $stock_db; ?>" required>

                        </div>
                        <div class="form-group">
                            <label for="">Stock Minimal</label>
                            <input type="number" min="1" step="1"
                            class="form-control" name="min_stock" value="<?php echo $min_stock_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Unite</label>
                            <input type="text" class="form-control"
                            name="satuan" value="<?php echo $satuan_db; ?>" required readonly>
                            
                        </div>

                        <div class="form-group">
                            <label for="">Categorie</label>
                            <input type="text" class="form-control"
                            name="category" value="<?php echo $category_db; ?>" required readonly>
                            
                        </div>

                        <div class="form-group">
                            <label for="">Marque</label>
                            <input type="text" class="form-control"
                            name="product_brand" value="<?php echo $productBrand_db; ?>" required readonly>
                        </div>

                        

                        <div class="form-group">
                            <label for="">Prix Achat</label>
                            <!--<input type="number" min="10" step="100"-->
                            <input type="number" min="10" class="form-control"
                            name="purchase_price" value="<?php echo $purchase_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Prix de vente</label>
                            <input type="number" min="100" class="form-control"
                            name="sell_price" value="<?php echo $sell_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Prix Min</label>
                            <input type="text" min="100" class="form-control"
                            name="min_price" value="<?php echo $min_db; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Discount</label>
                            <input type="text" class="form-control"
                            name="discount" value="<?php echo $discount_db; ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        

                        
                        <div class="form-group">
                            <label for="">Description Produit</label>
                            <textarea name="description" id="description"
                            cols="30" rows="10" class="form-control" required readonly><?php echo $desc_db; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Image Materiel</label>
                            
                            <img src="upload/<?php echo $product_img?>" alt="Preview" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"
                    name="update_product">Enregistrer</button>
                    <a href="product_shop_item.php" class="btn btn-warning">Retour</a>
                </div>
            </form>

        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'inc/footer_all.php';
 ?>