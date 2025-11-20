<?php

    
       function creation_panier () {


          if(!isset($_SESSION['panier'])){


            $_SESSION['panier'] = array();
            $_SESSION['panier']['libelleProduit'] = array();
            $_SESSION['panier']['qteProduit'] = array();
            $_SESSION['panier']['prixProduit'] = array();
            $_SESSION['panier']['verrou'] = false;


          }


          return true;

       }



       function ajouter_article($libelleProduit,$qteProduit,$prixProduit){


          if(creation_panier() && !isverouille()){

              $position_produit = array_search($libelleProduit, $_SESSION['panier']['libelleProduit']);

              if ($position_produit !== false) {

                $_SESSION['panier']['libelleProduit'][$position_produit] += $qteProduit;
              }

              else

              {

                  array_push($_SESSION['panier']['libelleProduit'], $libelleProduit);
                  array_push($_SESSION['panier']['qteProduit'], $qteProduit);
                  array_push($_SESSION['panier']['prixProduit'], $prixProduit);


              }


          }



          else {


                  echo "Erreur 001 !!!! Veuillez contacter l administrateur";


          }



       }




       function modifierQteProduit($libelleProduit,$qteProduit) {


            if(creation_panier() && !isverouille()) {


                  //echo " ".$qteProduit." OKOK" ;

                  if ($qteProduit > 0 ){

                    //echo "BlABlABlAxxxx";

                    $position_produit = array_search($libelleProduit, $_SESSION['panier']['libelleProduit']);

                    //echo "Position ".$position_produit. " Position";

                    if($position_produit !== false){

                      //echo "NDOMBA DE PORC ".$_SESSION['panier']['libelleProduit'][$position_produit]. "AK";

                      $_SESSION['panier']['qteProduit'][$position_produit] = $qteProduit;


                    }

                  }

                  else{

                      supprimerArticle($libelleProduit);
                  }

            } 

            else {

                      echo " Erreur On IT Veuillez contacter un administrateur Modif";

               }


       }





       function supprimerArticle($libelleProduit){

              if(creation_panier() && !isverouille()){

                  //echo " LA SUPPRESSSSSSSSSSSion ";

                  //echo count($_SESSION['panier']['libelleProduit']);

                  $tmp = array();
                  $tmp['libelleProduit'] = array();
                  $tmp['qteProduit'] = array();
                  $tmp['prixProduit'] = array();
                  $tmp['verrou'] = array();
                  
                  for ($i=0; $i < count($_SESSION['panier']['libelleProduit']) ; $i++) { 

                    //echo "AA";

                    if($_SESSION['panier']['libelleProduit'][$i] !== $libelleProduit){

                        array_push($tmp['libelleProduit'], $_SESSION['panier']['libelleProduit'][$i]);
                        array_push($tmp['qteProduit'], $_SESSION['panier']['qteProduit'][$i]);
                        array_push($tmp['prixProduit'], $_SESSION['panier']['prixProduit'][$i]);


                    }
                  }

                    $_SESSION['panier'] = $tmp;

                    unset($tmp);


              }

              else {

                    echo "  HErreur !!!! Veuillez contacter un administrateur 115";
              }


       }





       function montantGlobal(){

          $total = 0 ;

          for ($i=0; $i < count($_SESSION['panier']['libelleProduit']) ; $i++){

              #echo $_SESSION['panier']['qteProduit'][$i];
              #echo $_SESSION['panier']['prixProduit'][$i];

              $total = $_SESSION['panier']['qteProduit'][$i] * $_SESSION['panier']['prixProduit'][$i] + $total;

          }

          return $total;


       }


       function montantGlobalTVA(){

          $total = 0 ;

          for ($i=0; $i < count($_SESSION['panier']['libelleProduit']) ; $i++){

              $total += $_SESSION['panier']['qteProduit'][$i] * $_SESSION['panier']['prixProduit'];

          }

          return $total + $total * 0.1925;


       }





       function supprimerPanier(){

              if(isset($_SESSION['panier'])){

                unset($_SESSION['panier']);

              }

       }






      function isverouille(){


          if(isset($_SESSION['panier']) && $_SESSION['isverouille']){

            return true ;

          }

          else {

            return false;
          }


      }







       function compterArticles(){


          if(isset($_SESSION['panier'])) {

              return count($_SESSION['panier']['libelleProduit']);

          }


          else {

               return 0 ;

          }

       }






?>