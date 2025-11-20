<?php
    include_once'misc/plugin.php';
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['role']!="Admin"&&$_SESSION['role']!="Responsable"){
    header('location:index.php');
    }

    if($id=$_GET['id']){
    $select = $pdo->prepare("SELECT * FROM users WHERE user_id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    
    $user_id=$row['user_id'];
    $firstname=$row['firstname'];
    $middlename=$row['middlename'];
    $lastname=$row['lastname'];
    $address=$row['address'];
    $email=$row['email'];
    $contact=$row['contact'];
    $username=$row['username'];
    $password=$row['password'];
    $type=$row['type'];
    
    }else{
    header('location:customer.php');
    }

    if(isset($_POST['submit'])){
	
    $user_id=$_POST['user_id'];
    $firstname=$_POST['firstname'];
    $middlename=$_POST['middlename'];
    $lastname=$_POST['lastname'];
    $address=$_POST['address'];
    $email=$_POST['email'];
    $contact=$_POST['contact'];
    $username=$_POST['username'];
    $password=sha1($_POST['password']);
    $type=$_POST['type'];

    $id = $user_id;
		
                $update = $pdo->prepare("UPDATE users SET firstname=:firstname, middlename=:middlename, lastname=:lastname, address=:address, email=:email, contact=:contact, username=:username, password=:password, type=:type WHERE user_id = $id");

                                //$update->bindParam('user_id', $user_id);
                                $update->bindParam('firstname', $firstname);
                                $update->bindParam('middlename', $middlename);
                                $update->bindParam('lastname', $lastname);
                                $update->bindParam('address', $address);
                                $update->bindParam('email', $email);
                                $update->bindParam('contact', $contact);
								$update->bindParam('username', $username);
								$update->bindParam('password', $password);
								$update->bindParam('type', $type);
								
                                
                               

                                if($update->execute()){
								    echo'<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Modification reussie", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
                                    header('location:view_customer.php?id='.urlencode($id));
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
                <h3 class="box-title">Modifier Client</h3>
            </div>
            <form action="" method="POST" name="form_supplier"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">ID Client</label>
                            <input type="text" class="form-control"
                            name="user_id" value="<?php echo $user_id; ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Nom Client</label>
                            <input type="text" class="form-control"
                            name="firstname" value="<?php echo $firstname; ?>" required>
                        </div>
                        <div class="form-group">
                                    <label for="middlename">Second Nom</label>
                                    <input type="text" class="form-control" id="middlename" value="<?php echo $middlename; ?>" name="middlename" placeholder="Enter Second Nom" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Nom de Famille / Society</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname; ?>" placeholder="Enter lastname" required>
                                </div>
                        <div class="form-group">
                            <label for="">Adresse</label>
                            <input type="text" class="form-control"
                            name="address" value="<?php echo $address; ?>" required>
                        </div>
                         <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" min="10" class="form-control"
                            name="email" value="<?php echo $email; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="">Contact Client</label>
                            <!--<input type="number" min="10" step="100"-->
                            <input type="text" min="10" class="form-control"
                            name="contact" value="<?php echo $contact; ?>" required>
                        </div>
                       
                        <div class="form-group">
                                    <label for="username"> Login </label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" placeholder="Numero" readonly="" required>
                        </div>
                        <div>
                                	<label for="password"> Mot de Passe </label>
                                	<input type="password" id="password" name="password" placeholder="Password" class="form-control"  required>
                                </div>

                        <div class="form-group">
                                    <label>TYPE CLIENT</label>
                                    <select class="form-control" name="type" required>
                                    	<option value="particulier" selected=""><?php echo $type; ?></option>
                                        <option value="particulier">Particulier</option>
                                        <option value="entreprise">Entreprise</option>
                                    </select>
                                </div>
                        

                    
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"
                    >Enregistrer</button>
                    <a href="customer.php" class="btn btn-warning">Retour</a>
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