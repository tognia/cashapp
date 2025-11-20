<?php
	session_start();
	 require_once("configuration.php");
	 require_once("session.php");
     verifSession($_SESSION['droit']);
	  require_once("verifSubmit.php");
	  require_once("user.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
<style type="text/css">
<!--
@import url("css.css");
.Style3 {font-size: 10px}
.Style4 {font-size: 24px}
-->
</style>
</head>

<body bgcolor="#66CCFF"><div id="content">
<div id="error"></div>
<form action="envoi.php" method="post" name="form1" target="_self" class="tab" onsubmit="return verification();">
 <table width="469" border="0" class="tab1">
  <tr>
    <th colspan="2" scope="col">Infos Client</th>
    </tr>
  <tr>
    <td width="98"><div align="left">Code Fidelité</div></td>
    <td width="238"><input type="text" name="textfield" id="textfield"/></td>
  </tr>
  <tr>
    <td>Nom/Prénoms*</td>
    <td><input type="text" name="textfield2" id="textfield2" /><span id="plus1"></span></td>
  </tr>
  <tr>
    <td>Adresse</td>
    <td><input type="text" name="textfield3" id="textfield3" /></td>
  </tr>
  <tr>
    <td>N° Pièce</td>
    <td><input type="text" name="textfield4" id="textfield4" /></td>
  </tr>
  <tr>
    <td>C.Postal/Tél</td>
    <td><input name="textfield5" type="text" disabled="disabled" id="textfield5" value="237" size="7" maxlength="3"/>
      <input name="textfield6" type="text" id="textfield6" value="Tapez le numéro de téléphone" size="31" onblur="if(this.value=='') this.value='Tapez le numéro de téléphone';" onfocus="if(this.value=='Tapez le numéro de téléphone') this.value='';"/></td>
  </tr>
  <tr>
    <td>1111111</td>
    <td>2222222</td>
  </tr>
</table>
 <table width="497" border="0" class="tab1">
   <tr>
     <th colspan="2" scope="col">Infos destinataire</th>
    </tr>
   <tr>
     <td width="212">Destination*</td>
     <td width="275"><select name="select" id="select">
       <option> </option>
       </select>
      <select name="codeagence"><option value="<?php echo $_REQUEST['codeagence'] ?>" selected></option> 

	<?php 
$sql="SELECT * FROM agence ORDER BY codeagence";
$resultat=mysql_query($sql);
while ($rang=mysql_fetch_array($resultat)){
$codv=$rang['codeagence'];
$lib=$rang['nomagence'];
echo"<option value=";
echo $codv.">";
echo $lib;
echo "</option>";
}

?>	</select>      </td>
   </tr>
   <tr>
     <td>Bénéficiaire avec Pièce d'identité</td>
     <td><table width="200">
       <tr>
         <td><label>
           <input name="gpr" type="radio" id="gpr_0" value="oui" checked="checked" />
           Oui</label></td>
       <td><label>
           <input name="gpr" type="radio" id="gpr_1" value="non" />
           Non</label></td>
         <td><label>
           <input name="gpr" type="radio" id="gpr_2" value="tout" />
           Tout</label></td>
       </tr>
     </table>
     </td>
   </tr>
   <tr>
     <td>Code secret</td>
     <td><input name="textfield9" type="text" id="textfield9" size="35"/></td>
   </tr>
   <tr>
     <td>Nom/Prénoms*</td>
     <td><input name="textfield10" type="text" id="textfield10" size="35"/><span id="plus2"></span></td>
   </tr>
   <tr>
     <td>C.Postal/Tél</td>
     <td>
         <input name="textfield7" type="text" disabled="disabled" id="textfield7" value="237" size="7" maxlength="3"/>
         <input name="textfield8" type="text" id="textfield8" value="Tapez le numéro de téléphone" size="31"/>      </td>
   </tr>
   <tr>
     <td>11111111</td>
     <td>22222222</td>
   </tr>
 </table>
 <table width="1002" border="0" class="tab2">
   <tr>
     <th width="93" scope="col">Montant versé</th>
     <th width="293" scope="col"><input type="text" name="textfield11" id="textfield11" />
       <span class="Style3">F CFA</span></th>
     <th width="212" scope="col"><span class="Style3">FRAIS TTC</span>
       <input type="text" name="textfield12" id="textfield12" /></th>
     <th width="204" scope="col"><span class="Style3">Frais HT</span>
       <input type="text" name="textfield13" id="textfield13" /></th>
     <th width="178" scope="col"><span class="Style3">TVA</span>
       <input type="text" name="textfield14" id="textfield14" /></th>
   </tr>
   <tr>
     <td><input type="checkbox" name="checkbox" id="checkbox" /></td>
     <td>Répartition automatique</td>
     <td></td>
     <td></td>
     <td></td>
   </tr>
   <tr>
     <td>Mt à Envoyer*</td>
     <td><input type="text" name="textfield15" id="textfield15" />
       <span class="Style3">F CFA</span></td>
     <td colspan="2"><input name="textfield18" type="text" id="textfield18" size="55" /></td>
     <td><span class="Style4">CFA</span></td>
   </tr>
   <tr>
     <td>Mt Converti</td>
     <td><input type="text" name="textfield16" id="textfield16" />
       <span class="Style3">       F CFA</span></td>
     <td>Taux de Change</td>
     <td>1</td>
     <td></td>
   </tr>
   <tr>
     <td>Monnaie rendue</td>
     <td><input type="text" name="textfield17" id="textfield17" />
       <span class="Style3">F CFA</span></td>
     <td></td>
     <td></td>
     <td></td>
   </tr>
 </table>
 <p><input name="" type="submit" value="Send"/></p>
</form>
</div>
<script type="text/javascript" src="interface.js"></script>
</body>
</html>
