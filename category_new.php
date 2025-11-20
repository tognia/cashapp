<?php
  include_once'db/connect_db.php';
  if($_SESSION['role']!=="Admin" && $_SESSION['role']!=="Responsable" ){
    header('location:index.php');
  }


  if(isset($_POST['category_new'])){

    $category_new = $_POST['category_new'];
    // if(isset($_POST['category'])){

      $select = $pdo->prepare("SELECT cat_name FROM tbl_category WHERE cat_name='$category_new'");
      $select->execute();

      if($select->rowCount() > 0 ){
          echo'<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Categorie Existente ou Autre Probleme", "warning", {
              button: "Continue",
                  });
              });
              </script>';
          }else{
            $insert = $pdo->prepare("INSERT INTO tbl_category(cat_name,cat_parent,cat_level) VALUES(:category,'Aucune',3)");

      $insert->bindParam(':category', $category_new);
			// $insert->bindParam(':categoryparent',$category_newparent);
			// $insert->bindParam(':level',$level);

            if($insert->execute()){
              echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Success", "Categorie enregistree avec succes", "success", {
              button: "Continue",
                  });
              });
              </script>';
            }
          }
  } else {
    $category_new = $_POST['category'];
  }
?>




