
    <section class="u-align-center u-clearfix u-section-2" id="sec-95d8">
      
              <div class="box-body" style="overflow-x:auto;">
            <table class="table table-striped" id="myCategory">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Libelle</th>
                        <th>Categorie Parent</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody>
                <?php
                $select = $pdo->prepare('SELECT * FROM tbl_category WHERE cat_level=3 ORDER BY cat_name');
                $select->execute();
                while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                  <tr>
                    <td><?php echo $row->cat_id; ?></td>
                    <td><?php echo $row->cat_name; ?></td>
                    <td><?php echo $row->cat_parent; ?></td>
                    
                    <td>
                        <a href="edit_category.php?id=<?php echo $row->cat_id; ?>"
                        class="btn btn-info btn-sm" name="btn_edit"><i class="fa fa-pencil"></i></a>
                        <a href="delete_category.php?id=<?php echo $row->cat_id; ?>"
                        onclick="return confirm('Confirmer votre choix')"
                        class="btn btn-danger btn-sm" name="btn_delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php
                }
                ?>

                </tbody>
            </table>
          </div>



    </section>