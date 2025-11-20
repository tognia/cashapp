
   function verifievide(form1) { // Fonction appelée par le bouton
      
      if (form1.codepaysenvoi.value==""|| form1.codepaysrecep.value=="") { // Plus de 1 clic
         alert("Selectionner les pays envoi et reception")
				form1.codepaysenvoi.focus()
				return false
      
      }
   }
   
   		function reFresh() {
 		 location.reload(true);
							}
							
							
	function checkRefresh()
	
{
	
	
	if( document.form1.visited.value == "" )
	{
		// This is a fresh page load
		document.form1.visited.value = "1";

		// You may want to add code here special for
		// fresh page loads
	}
	else
	{	
		window.open('envoipg.php?soumis=0','_blank',location=0, directories=0, status=0, scrollbars=1, resizable=0, copyhistory=0);return(false);  
		// document.location="suite.php";
	}
} 					
   
   
   function res() { // Fonction appelée par le bouton
      
      	
      var montant=document.form1.montantenvoi.value;
	   var montantotal=montant*0.1925;
		
		document.form1.amount.value = montantotal;
		
				//form1.codepaysenvoi.focus()
				//return false
      //document.forms["form2"].elements["borneinf"].value=document.forms["form1"].elements["codepaysrecep"].value;
	  //return true;

  			 }
   
   		function ExactRound(a,b,e){
a=String(a)
b=String(b)
var deci=( a.split('\.')[1].length > b.split('\.')[1].length )?a.split('\.')[1].length:b.split('\.')[1].length;
var c= Number(a) + Number(b);
var expo= (Math.pow(10,deci))
var result=((Math.round(c*expo)/expo).toFixed(e));
return result;
}

///////////////////////////////////////////////////////  DEBUT RECHERCHE CLIENT EXPEDITEUR //////////////////////////////////////


function rechercheClient() {
 
		if(isNaN(Number(document.form1.telexp.value)))
{
	alert("veuillez entrez des Chiffres svp!!!");
	var x=document.getElementById("telexp");
	x.innerHTML="*";
	document.form1.telexp.focus();
	//return false;
}


		var b0=Number('<?php echo $_SESSION['sizetelclient']; ?>');
		var b1=Number('<?php echo $_SESSION['sizetcodefidel']; ?>');
		var b2=Number('<?php echo $_SESSION['sizetnomclient']; ?>');
		var b3=Number('<?php echo $_SESSION['sizetpreclient']; ?>');

 
 	    var i=0;
		
		var tb0=new Array();
		var tb1= new Array();
		var tb2= new Array();
		var tb3= new Array();
		
	
	   <?php
	   		$i=0;
			$a=$_SESSION['sizetelclient'];
				for ($i=0;$i<$a;$i++)
				 {
			  
	   ?>
	   
	   	tb0[<?php echo $i;?>]='<?php echo $_SESSION['telclient'][$i];?>';
		tb1[<?php echo $i;?>]='<?php echo $_SESSION['tcodefidel'][$i];?>';
		tb2[<?php echo $i;?>]='<?php echo $_SESSION['tnomclient'][$i];?>';
		tb3[<?php echo $i;?>]='<?php echo $_SESSION['tpreclient'][$i];?>';
	   
	   <?php
	   		  }
	   ?>
	   	
		var mte=Number(document.form1.telexp.value);
		var frht=1;
		var boi=1; var b00=1; var b01=1; var b02=1; var b03=1;
		//var bos=50000;
		var j=2;
	   
	   for (j=0;j<b0;j++)
			{
			boi=Number(tb0[j]);	
			//bos=Number(tbs[j]);
			if (mte==boi)
			//if ((frht>=1)&&(frht<=4))		
				{
				b00=tb0[j];
				b01=tb1[j];
				b02=tb2[j];
				b03=tb3[j];
				//frht=4;
				//break;
				}
	
			}
				
	 document.form1.codefidelexp.value=b01;
	 document.form1.nomexp.value=b02;
	
	document.form1.prenexp.value=b03; //bon
	
	}

//////////////////////////////////////  FIN RECHERCHE CLIENT EXPEDITEUR ///////////////////////////////////////////


///////////////////////////////////////////////////////  DEBUT RECHERCHE CLIENT BENEFICIAIRE //////////////////////////////////////


function rechercheClientben() {
 
		if(isNaN(Number(document.form1.telben.value)))
{
	alert("veuillez entrez des Chiffres svp!!!");
	var x=document.getElementById("telben");
	x.innerHTML="*";
	document.form1.telben.focus();
	//return false;
}


		var b0=Number('<?php echo $_SESSION['sizetelclient']; ?>');
		var b1=Number('<?php echo $_SESSION['sizetcodefidel']; ?>');
		var b2=Number('<?php echo $_SESSION['sizetnomclient']; ?>');
		var b3=Number('<?php echo $_SESSION['sizetpreclient']; ?>');

 
 	    var i=0;
		
		var tb0=new Array();
		var tb1= new Array();
		var tb2= new Array();
		var tb3= new Array();
		
	
	   <?php
	   		$i=0;
			$a=$_SESSION['sizetelclient'];
				for ($i=0;$i<$a;$i++)
				 {
			  
	   ?>
	   
	   	tb0[<?php echo $i;?>]='<?php echo $_SESSION['telclient'][$i];?>';
		tb1[<?php echo $i;?>]='<?php echo $_SESSION['tcodefidel'][$i];?>';
		tb2[<?php echo $i;?>]='<?php echo $_SESSION['tnomclient'][$i];?>';
		tb3[<?php echo $i;?>]='<?php echo $_SESSION['tpreclient'][$i];?>';
	   
	   <?php
	   		  }
	   ?>
	   	
		var mte=Number(document.form1.telben.value);
		var frht=1;
		var boi=1; var b00=1; var b01=1; var b02=1; var b03=1;
		//var bos=50000;
		var j=2;
	   
	   for (j=0;j<b0;j++)
			{
			boi=Number(tb0[j]);	
			//bos=Number(tbs[j]);
			if (mte==boi)
			//if ((frht>=1)&&(frht<=4))		
				{
				b00=tb0[j];
				b01=tb1[j];
				b02=tb2[j];
				b03=tb3[j];
				//frht=4;
				//break;
				}
	
			}
				
	 //document.form1.codefidelexp.value=b01;
	 document.form1.nomben.value=b02;
	
	document.form1.prenben.value=b03; //bon
	
	}

//////////////////////////////////////  FIN RECHERCHE CLIENT BENEFICIAIRE ////////////




///////////////////////////////////////////////////////  CALCUL DES FRAIS //////////////////////////////////////


function cal() {
 
 
		if(isNaN(Number(document.form1.montantenvoi.value)))
{
	alert("veuillez entrez des Chiffres svp!!!");
	var x=document.getElementById("montantverse");
	x.innerHTML="*";
	document.form1.montantverse.focus();
	//return false;
}

else if(isNaN(Number(document.form1.montantenvoi.value)))
{
	alert("veuillez entrez des Chiffres svp!!!");
	var x=document.getElementById("montantenvoi");
	x.innerHTML="*";
	document.form1.montantenvoi.focus();
	//return false;
}

		var bi=Number('<?php echo $_SESSION['sizetborneinf']; ?>');
		var bs=Number('<?php echo $_SESSION['sizetbornesup']; ?>');
		var mt=Number('<?php echo $_SESSION['sizetmontant']; ?>');
 //document.form1.amount.value=bi+bs+mt;
 
 	    var i=0;
		//var s=Number('<?php //echo($_SESSION['tborneinf'][1]); ?>');
		var tbi=new Array();
		var tbs= new Array();
		var tmt= new Array();
		
		
		
	   // tbi='<?php echo $_SESSION['tborneinf']; ?>';
		//tbs= '<?php echo $_SESSION['tbornesup']; ?>'
		//tmt= '<?php echo $_SESSION['tmontant']; ?>'
		
		
		//for (i=0;i<bs;i++)
	/*{
			
			
	tbi[i]=Number('<?php //echo $_SESSION['tborneinf'][1]; ?>');
	tbs[i]=Number('<?php //echo $_SESSION['tbornesup']; ?>')
	tmt[i]=Number('<?php //echo $_SESSION['tmontant'];?>')
		//s=s+tmt[i]
	}*/
	//tmt[1]=56;
	   <?php
	   		$i=0;
			$a=$_SESSION['sizetborneinf'];
				for ($i=0;$i<$a;$i++)
				 {
			  
	   ?>
	   
	   	tbi[<?php echo $i;?>]='<?php echo $_SESSION['tborneinf'][$i];?>';
		tbs[<?php echo $i;?>]='<?php echo $_SESSION['tbornesup'][$i];?>';
		tmt[<?php echo $i;?>]='<?php echo $_SESSION['tmontant'][$i];?>';
	   
	   <?php
	   		  }
	   ?>
	   	
		var mte=Number(document.form1.montantenvoi.value);
		var frht=1;
		var frttc=1;
		var boi=1;
		var bos=50000;
		var j=2;
	   
	   for (j=0;j<bs;j++)
			{
			boi=Number(tbi[j]);	
			bos=Number(tbs[j]);
			if ((Number(mte)>=boi)&&(Number(mte)<=bos))
			//if ((frht>=1)&&(frht<=4))		
				{
				var frttc=Number(tmt[j]);
				frht=Math.round(Number(tmt[j])/1.1925,2);
				//frht=4;
				//break;
				}
	
			}
		if ((Number(mte)>10000000)||(Number(mte)<=0))
			{
				alert("LE MONTANT A ENVOYER N'EST PAS VALIDE");
				var x=document.getElementById("montantenvoi");
	x.innerHTML="*";
	document.form1.montantenvoi.focus();
			}
		
	 document.form1.fraisht.value=frht;
	//var frttc=Math.round(29+frht+frht*0.1925,2);
	
	document.form1.fraisttc.value=frttc; //bon
	
	//document.form1.amount.value=Number(tbi[2])+Number(tbs[2])+Number(tmt[2]);
		document.form1.amount.value=Number(mte)+frttc; //bon
		document.form1.monnaierendue.value=Number(document.form1.montantverse.value)-(Number(mte)+frttc);
 

}

//////////////////////////////////////  FIN CALCUL DES FRAIS ///////////////////////////////////////////



function cal1()
	{	

		document.form1.monnaierendue.value=Number(document.form1.montantverse.value)-(Number(document.form1.montantenvoi.value)+Number(document.form1.fraisttc.value));
 
}

function desactive() { 
//window.history.forward();
window.history.go(1)=false;
setTimeout('redirection()',1000);
} 

function gesclient() { 

} 

function redirection(){
//getElementById("liste_10").style.visibility="hidden";
document.location="acceuil.php";
window.alert("TEMPS DE TRANSACTION DEPASSE");
}

function fct_masquer(id){ 
getElementById(id).style.visibility="hidden"; 

} 	

function pasdecni() {
 
		//if(document.form1.piecebenvalide1.value=="non")
//{
	getElementById("liste_10").style.visibility="hidden";
		
//}
   }
   
   function pasdecni1() {
	
	//	if(document.form1.piecebenvalide1.value=="oui")
//{
	getElementById("lbtypepieceben").style.visibility="hidden";
	getElementById("typepieceben1").style.visibility="hidden";
	
//}


	}

///////////////////////////////////// 

// ==================
//	Activations - Désactivations
// ==================
function GereControle(Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
	if (Masquer=='1')
		objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
	else
		objControle.disabled=(objControleur.checked==true)?false:true;
	return true;
}

////////////////////////////////// GERE CONTROL BEGIN //////////////////////////////////

function GereControlebegin(Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
setTimeout('redirection()',100000000);
	if (Masquer=='1')
		objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
	else
		objControle.disabled=(objControleur.checked==true)?false:true;
	return true;
}
