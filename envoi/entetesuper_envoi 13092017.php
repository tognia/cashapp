

<style type="text/css">
<!--
body {
	background-color: #F5F5F5;
	text-align:center;
}
.Style2 {
	font-family: Calibri;
	font-size: 20px;
	color: #FFFFFF;
}
.Style3 {font-family: Calibri; font-size: 15px; color: #000000; }
.Style4 {
	font-family: Calibri;
	font-style: italic;
	font-weight: bold;
}
.Style5 {
	color: #FFFFFF;
	font-size: 16px;
}
.Style6 { color: #FF9900;
         font-size: 16px}
.Style9 {
color:#FF6600;
background-color:#FF6600

         }
		 
-->
</style>



<link type="text/css" rel="stylesheet" href="assets/css/main_envoi.css" />




<SCRIPT language="javascript">
function nouvelleFenetre(url) { 
propriete = "top=0,left=0,resizable=yes, status=no, directories=no, addressbar=no, toolbar=no, scrollbars=yes, menubar=no, location=no, statusbar=no" 
propriete += ",width=" + screen.availWidth + ",height=" + screen.availHeight; 
win = window.open(url,"ton_titre", propriete) 
} 
function deconnected(){
 confirm("VOULEZ VOUS VOUS DECONNECTER ?");
 document.location="deconnected.php";

}

</SCRIPT>

<label>
<?php 
//echo' 
//<input type="image" name="imageField" height="250" width="70%" src="images/header_img_bg.gif">
 //';
$datetoday = date("Y-m-d");
   ?>
<center>
   <img src="images/banner21.png" align="middle" width="100%" height="130" border="1"   /><br/>
 </center>
   
 </label> 
<table align="center" width="900"> 
<tr align="center">
<td align="center" width="99%">
<center>
<!--<div id="header-wrapper" class="wrapper">
<div id="header">-->
<nav id="nav">
<ul id="menu" >

        <li>
                <div align="center"><a name="accueil" href="acceuil.php?login=<?php echo trim($_SESSION['login']); ?>"><b><font color="#000000">ACCUEIL</font></b></a>
                        </div>
        </li>
        
        				
        
        <li>
                <a href="#"><b><font color="#000000">Operations</font></b></a>
                 <ul>
                 
                 	<?php
									if(($_SESSION['droit']=="user")|| ($_SESSION['droit']=="superadmin"))
										{
							?>
                        <li>
                                <a href="envoipg.php?soumis=0&envoilink&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>"> ENVOI ARGENT</a><!-- onclick="nouvelleFenetre(this.href);return false;"-->
                        </li>
                        <li>
                                <a href="receptionpg.php?soumis=0&paylink=1&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">PAYMENT</a>
                        </li>
                        
                      
                        <li>
                            <?php
							if($_SESSION['codepartenaire']=="eh"){
							?>
							
                                <a href="envoicadeau.php?soumis=0&envoilink&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>"> ENVOI CADEAU</a>
                        </li>
                        
                        <?php
						
						     }
									}
							?>
                        
                        
						<li>
                                <a href="transactionstatus.php?soumis=0&envoilink&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">STATUS TRANS.</a><!-- onclick="nouvelleFenetre(this.href);return false;"-->
                        </li>
                          <?php
								
									if($_SESSION['droit']=="operator" || $_SESSION['droit']=="superadmin")
										{
								
						?>
                         <li>
                                <a href="validationpg.php?soumis=0&paylink=1&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">VALIDATION ENVOI</a>
                        </li>
                         <?php
								}
						?>
                        
                        	<?php
									//if($_SESSION['droit']=="superadmin"||$_SESSION['droit']=="admin")
									   if($_SESSION['droit']=="superadmin" ||$_SESSION['droit']=="admin"||$_SESSION['droit']=="operator")
										{
							?>
	    <li>
                                <a href="transactionmodifiepg.php?soumis=0&envoilink&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>"> MODIFIER MANDAT</a><!-- onclick="nouvelleFenetre(this.href);return false;"-->
                        </li>
						<li>
                                <a href="transactioncancel.php?soumis=0&envoilink&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>"> ANNULER MANDAT</a><!-- onclick="nouvelleFenetre(this.href);return false;"-->
                        </li>
                        
                          <?php
									}
							?>
                            
                           
						
          </ul>
        
        </li>
        
			
			<?php
			
			include("consultations.php");
			
			 //if(($_SESSION['droit']=="superadmin")||($_SESSION['droit']=="admin"))
			 if($_SESSION['droit']=="superadmin")
						{
						 ?>
        <li>
                <a href="#"><b><font color="#000000">Configuration</font></b></a>
                <ul>
						
                       
										<li><a href="partenairepg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Partenaire</a></li>
                                        <li><a href="partenaireactivation.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">ACTIV_Partenaire</a></li>
                                        <li><a href="partenaireappropg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">APPRO_Partenaire</a></li>
                                        <li><a href="agencepg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Agence</a></li>
                                        <li><a href="initialeagencepg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Agence_Initiale</a></li>
                                        <li><a href="userpg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Utilisateur</a></li>
                                        <li><a href="villepg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Ville</a></li>
										<li><a href="payspg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Pays</a></li>
										<li><a href="tarifpg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Tarifs</a></li>
                                        <li><a href="clientpg.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Gestion Clients</a></li>
                                        
										<!--<li><a href="villepg.php?soumis=0&nomagence=<?php //echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Tarifs Par Pays</a></li>-->
                                <!--</ul>

                        </li>-->

                </ul>
        </li>
      	<?php 
		}
		
		
		 ?>  
      	  
		  
		  <?php //if($_SESSION['droit']=="user")
		          if(($_SESSION['droit']=="user")||($_SESSION['droit']=="admin"))
						{
						 ?>
						<li>
                <a href="#"><b><font color="#000000">Parametres</font></b></a>
                <ul>
                        <li>
                                <a href="userchangeprofil.php?soumis=0&nomagence=<?php echo $_SESSION['nomagence']; ?>&codeagence=<?php echo $_SESSION['codeagence']; ?>">Modifier compte</a>
			            </li>
						  <!-- <li>
                                <a href="UserChangePhoto.php?soumis=0&prenom=<?php //echo $_SESSION['prenom']; ?>&nom=<?php //echo $_SESSION['nom']; ?>&nomagence=<?php //echo $_SESSION['nomagence']; ?>&codeagence=<?php //echo $_SESSION['codeagence']; ?>">Changer Photo</a>
			            </li>-->
                        						
                </ul>
				
			</li>	
				<?php 
				    } 
				?> 		  
		  
        
        <li>
				 <a href="acceuil.php?action=0&soumis=0&deconnect=0" <?php echo ' onclick="return confirm(\'Voulez-vous vraiment Quitter Ti_Transfert Cliquer su OK pour Quitter?\')"'; 
	?>><b><font color="#000000"> DECONNEXION</font></b></a>			
		</li>
</ul>
<!--</div>
</div>-->
</center>

</td>
</tr>
</table> 
<!-- Scripts -->

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/skel-viewport.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
 <table align="center" width="100%">
 <tr>
 <td width="95%" align="center">           

