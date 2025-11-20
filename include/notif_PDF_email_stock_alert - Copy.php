
<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//include 'includes/session.php';

	//////hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh

	//if(isset($_POST['reset'])){
	//if(isset($_GET['order'])&& ($_SESSION['create_order'] == 0)){

		$m = $_SESSION['magasin'];
		$select0 = $pdo->prepare("SELECT * FROM agence WHERE code_agence = '$m' ");
        $select0->execute();
        $row0=$select0->fetch(PDO::FETCH_OBJ);
        $email = $row0->email;
        $_SESSION['em'] = $email;
		//$email = $_SESSION['email'];

		//$password = sha1("12345678");

		
		/*$select1 = $pdo->prepare("SELECT count(user_id) as t FROM users WHERE email = '$email' ");
        $select1->execute();
        $row1=$select1->fetch(PDO::FETCH_OBJ);
        $total = $row1->t;


		$select = $pdo->prepare("SELECT * FROM users WHERE email = '$email'");
				    $select->execute();
				    $row = $select->fetch(PDO::FETCH_ASSOC);


		$id = $_SESSION['invoice_id'];
				$select2 = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE invoice_id=$id");
				$select2->execute();
				$row2 = $select2->fetch(PDO::FETCH_OBJ);*/

				//$message = "COMMANDE Num : ".$id." <br>";

		/*$message = for ($i=0; $i <$_SESSION['count_alert'] ; $i++) { 
			echo "Item Stock Alert : ";
			echo "ID: ".$_SESSION['tab_alert']['id'][$i]." ";
            echo "Code: ".$_SESSION['tab_alert']['code'][$i]." ";
            echo "Libelle:".$_SESSION['tab_alert']['name'][$i]." ";
            echo "Stock: ".$_SESSION['tab_alert']['stock'][$i]." ";
            echo "Stock Alert: ".$_SESSION['tab_alert']['stock_min'][$i]." ";
            echo '<br>';
		}*/


		$message = "info Alerte";

			/*while($item = $select2->fetch(PDO::FETCH_OBJ)){

				}*/



                                
		//echo $email;


		//echo "33333".$row['email']."44444";


		//////////////////////////////////////////////////////////////////////

			//if($row['numrows'] > 0){
		  //if($_SESSION['count_alert'] > 0){
			//generate code
			//$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			//$code=substr(str_shuffle($set), 0, 15);
			//$password = sha1("12345678");

			//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
			try{
				
				/*$stmt = $conn->prepare("UPDATE users SET password=:code WHERE user_id=:id");
				$stmt->execute(['code'=>$code, 'id'=>$row['user_id']]);*/

				/*$update = $pdo->prepare("UPDATE users SET password=:password WHERE email = $email");

                                $update->bindParam('email', $email);
                                $update->bindParam('password', $password);*/
				
				
				/*$message = "
					<h2>Nouvelle Commande</h2>
					<p>Your Account:</p>
					<p>Email: ".$_SESSION['username']."</p>
					<p>Commande Num : <h3> ".$id."</h3><br>".
					"<p>Total : <h3> ".$_SESSION['totalcommande']." FCFA</h3><br>".
					"<p>Date : <h3> ".$_SESSION['datecommande']."</h3><br>".
					"<a href = https://nuvibz.com/bevilec/shop/commandes/commande_".$id.".pdf";*/


					/*$message = '
					<h2>PRODUIT NIVEAU ALERTE</h2>
					<p>Your Account:</p>
					<p>Email: '.$_SESSION['username'].'</p>
					<p>Commande Num : <h3> '.$id.'</h3><br>'.
					'<p>Total : <h3> '.$_SESSION['totalcommande'].' FCFA</h3><br>'.
					'<p>Date : <h3> '.$_SESSION['datecommande'].'</h3><br>'.
					'<a href = "https://nuvibz.com/bevilec/shop/commandes/commande_'.$_SESSION['username'].'_'.$id.'.pdf">TELECHARGER FACTURE COMMANDE</a>';*/

					//echo $message;


				//Load phpmailer
	    		require 'vendor/autoload.php';

	    		$mail = new PHPMailer(true);                             
			    try {
			        //Server settings
			        /*$mail->isSMTP();                                     
			        $mail->Host = 'mail44.lwspanel.com';                      
			        $mail->SMTPAuth = true;                               
			        $mail->Username = 'commercial@nuvibz.com';     
			        $mail->Password = 'Eyezonkamer1@';                    
			        $mail->SMTPOptions = array(
			            'ssl' => array(
			            'verify_peer' => false,
			            'verify_peer_name' => false,
			            'allow_self_signed' => true
			            )*/

			        $mail->isSMTP();                                     
			        $mail->Host = 'smtp.gmail.com';                      
			        $mail->SMTPAuth = true;                               
			        $mail->Username = 'henribpeace@gmail.com';     
			        $mail->Password = 'ezkNow1&@';                    
			        $mail->SMTPOptions = array(
			            'ssl' => array(
			            'verify_peer' => false,
			            'verify_peer_name' => false,
			            'allow_self_signed' => true
			            )
			        ); 

			        //$fic = "commande_".$id.".pdf";

			        $mail->SMTPSecure = 'ssl';                           
			        $mail->Port = 465;                                   

			        $mail->setFrom('henribpeace@gmail.com');
			        
			        //Recipients
			        $mail->addAddress($email);              
			        $mail->addReplyTo('henribpeace@gmail.com');

			       	
			        //Content
			        $mail->isHTML(true);                                  
			        $mail->Subject = 'BEVILEC_ Alert _ Stock';
			        $mail->Body    = $message;
			        //$mail->AddAttachment('../commandes/'.$fic);
			        //$mail->AddAttachment('../commandes/commande_'.$id.'.pdf');

			        $mail->send();

			        $_SESSION['success'] = 'Alerte envoyee avec success';
			     
			    } 
			    catch (Exception $e) {
			        $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
			    }
			}


			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}


			//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||


			//*******************************************************************


			

	//////hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh

	//header('location: password_forgot.php');
	//header('refresh:2;misc/nota.php');

?>