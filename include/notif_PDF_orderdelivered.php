
<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//session_start();

	//include 'includes/session.php';

	//////hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh

	//if(isset($_POST['reset'])){
	if(isset($_GET['delivered'])&& ($_SESSION['delivered_order'] == 0)){

		$email = $_SESSION["email_client"];

		//$password = sha1("12345678");

		
		$select1 = $pdo->prepare("SELECT count(user_id) as t FROM users WHERE email = '$email' ");
        $select1->execute();
        $row1=$select1->fetch(PDO::FETCH_OBJ);
        $total = $row1->t;


		$select = $pdo->prepare("SELECT * FROM users WHERE email = '$email'");
				    $select->execute();
				    $row = $select->fetch(PDO::FETCH_ASSOC);


		$id =  $_SESSION["delivered_invoice"];

				$select2 = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE invoice_id=$id");
				$select2->execute();
				//$row2 = $select2->fetch(PDO::FETCH_OBJ);

				//$message = "COMMANDE LIVREE Num : ".$id." <br>";

			while($item = $select2->fetch(PDO::FETCH_OBJ)){


					$total_invoice = number_format($item->total,0,null," ")." FCFA";
					$datecommande = $item->date_paiement ;
					$infos = $item->infos_paiement ;

			}



                                
		echo $id .$email.$_SESSION["id_client"].$total_invoice.$datecommande.$infos;


		//echo "33333".$row['email']."44444";


		//////////////////////////////////////////////////////////////////////

			//if($row['numrows'] > 0){
		  if($total > 0){
			//generate code
			//$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			//$code=substr(str_shuffle($set), 0, 15);
			//$password = sha1("12345678");

			//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
			try{


						
				$message = '
					<h2>DELIVERED</h2>
					<p>Your Account:</p>
					<p>Username: '.$_SESSION["id_client"].'</p>'.
					'<p>Commande Livree Num : <h3> '.$id.'</h3><br>'.
					'<p>Total : <h3> '.$total_invoice.' FCFA</h3><br>'.
					'<p>Date : <h3> '.$datecommande.'</h3><br>'.
					'<p>Infos Paiement : <h3> '.$infos.'</h3><br>'.
					'<a href = "http://localhost/bevilec/app/commandes/commande_Livree_'.$_SESSION["id_client"].'_'.$id.'.pdf">TELECHARGER FACTURE COMMANDE</a>';

					//echo $message;


				//Load phpmailer
	    		require 'vendor/autoload.php';

	    		echo "hdhdhdhdhdhdh789";

	    		$mail = new PHPMailer(true);                             
			    try {
			        //Server settings
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
			        $mail->Subject = 'BEVILEC_ Order Delivered_'.$id;
			        $mail->Body    = $message;
			        //$mail->AddAttachment('../commandes/'.$fic);
			        //$mail->AddAttachment('../commandes/commande_179.pdf');



			        $mail->send();

			        $_SESSION['success'] = 'Notification Commande Livree avec success';
			     
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


			try{
				
					
				$message = '
					<h2>DELIVERED</h2>
					<p>Commande Client:</p>
					<p>Username: '.$_SESSION["id_client"].'</p>'.
					'<p>Commande Livree Num : <h3> '.$id.'</h3><br>'.
					'<p>Total : <h3> '.$total_invoice.' FCFA</h3><br>'.
					'<p>Date : <h3> '.$datecommande.'</h3><br>'.
					'<p>Infos Paiement : <h3> '.$infos.'</h3><br>'.
					'<a href = "http://localhost/bevilec/app/commandes/commande_Livree_'.$_SESSION["id_client"].'_'.$id.'.pdf">TELECHARGER FACTURE COMMANDE</a>';

					//echo $message;


				//Load phpmailer
	    		require 'vendor/autoload.php';

	    		$mail = new PHPMailer(true);                             
			    try {
			        //Server settings
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
			        $mail->SMTPSecure = 'ssl';                           
			        $mail->Port = 465;                                   

			        $mail->setFrom('henribpeace@gmail.com');
			        
			        //Recipients
			        $mail->addAddress('togniahenri@gmail.com');              
			        $mail->addReplyTo('henribpeace@gmail.com');
			       
			        //Content
			        $mail->isHTML(true);                                  
			        $mail->Subject = 'BEVILEC_ Order_'.$id;
			        $mail->Body    = $message;

			        $mail->send();

			        $_SESSION['success'] = 'Commande envoyee avec success';
			     
			    } 
			    catch (Exception $e) {
			        $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
			    }
			}


			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}



			//********************************************************************

			




		}
		else{
			$_SESSION['error'] = 'Email inexistant';
		}





		//////////////////////////////////////////////////////////

		//$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Input email associated with account';
	}


	//////hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh

	header('location: password_forgot.php');
	//header('refresh:2;misc/nota.php');

?>