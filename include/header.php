<!DOCTYPE html>
<html style="font-size: 16px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="INTUITIVE, inspiration">
    <meta name="description" content="">
    <meta name="page_type" content="np-template-header-footer-from-plugin">

    <title>BEVILEC-SHOP</title>

    <link rel="stylesheet" href="nicepage.css" media="screen">
    <link rel="stylesheet" href="Acceuil.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap.rtl.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap.rtl.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-grid.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-grid.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-grid.rtl.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-grid.rtl.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-reboot.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-reboot.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-reboot.rtl.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-reboot.rtl.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-utilities.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-utilities.css" media="screen">

    <link rel="stylesheet" href="css/bootstrap-utilities.rtl.min.css" media="screen">
    <link rel="stylesheet" href="css/bootstrap-utilities.css" media="screen">

    <link rel="stylesheet" href="css/sidebar.css" media="screen">

    <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
    <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>




    <meta name="generator" content="Nicepage 3.11.0, nicepage.com">
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    
    
    
    
    <script type="application/ld+json">{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": "",
		"url": "index.html",
		"logo": "images/BEVILECLOGO.png"
}</script>
    <meta property="og:title" content="BEVILEC-SHOP">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#478ac9">
    <link rel="canonical" href="index.html">
    <meta property="og:url" content="index.html">


          <?php
              //require_once("nav-bar.php");
  
          ?>


  </head>


  <body data-home-page="Acceuil.html" data-home-page-title="Acceuil" class="u-body">

    <header class="u-align-center-sm u-align-center-xs u-clearfix u-header u-header" id="sec-ce98"><div class="u-clearfix u-sheet u-sheet-1">
        <a href="index.php" class="u-image u-logo u-image-1" data-image-width="209" data-image-height="228">
          <img src="images/BEVILECLOGO.png" class="u-logo-image u-logo-image-1" data-image-width="63">
        </a>
        <nav class="u-align-left u-menu u-menu-dropdown u-offcanvas u-menu-1" data-responsive-from="MD">
          <div class="menu-collapse" style="font-size: 1rem;">
            <a class="u-button-style u-nav-link" href="#" style="font-size: calc(1em + 6px);">
              <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 302 302" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-8a8f"></use></svg>
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="svg-8a8f" x="0px" y="0px" viewBox="0 0 302 302" style="enable-background:new 0 0 302 302;" xml:space="preserve" class="u-svg-content"><g><rect y="36" width="302" height="30"></rect><rect y="236" width="302" height="30"></rect><rect y="136" width="302" height="30"></rect>
</g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
            </a>          </div>


                                    <!-- MENU HEADER DEBUT-->



          <div class="u-custom-menu u-nav-container">

          

<li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="boutique.php" style="padding: 10px 28px;">Produits</a>

                              <!-- NIVEAU 1 MENU PRODUITS -->

  <div class="u-nav-popup">

    <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-2">

      <?php
                $select = $pdo->prepare("SELECT * FROM tbl_category WHERE cat_level = 1");
                $select->execute();
                while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                 <?php //echo $row->cat_id; ?>
                 
                          <!-- NIVEAU 2 MENU PRODUITS -->
                        <li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="boutique.php?cat1=<?php echo $row->cat_name;?>"><?php echo $row->cat_name;?></a>
                          <?php $name1 = $row->cat_name ; ?>

                          <div class="u-nav-popup">
                            <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-2"> 

                                                  <?php
                $select1 = $pdo->prepare("SELECT * FROM tbl_category WHERE cat_parent = '$name1'");
                $select1->execute();
                while($row1=$select1->fetch(PDO::FETCH_OBJ))
                  { ?>
                        <!-- NIVEAU 3 MENU PRODUITS -->

                        <li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="boutique.php?cat2=<?php echo $row1->cat_name;?>"><?php echo $row1->cat_name;?></a>

                                          <?php $name2 = $row1->cat_name ; ?>

                                                        <div class="u-nav-popup">
                                                          <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-2"> 

                                                                                <?php
                                              $select2 = $pdo->prepare("SELECT * FROM tbl_category WHERE cat_parent = '$name2'");
                                              $select2->execute();
                                              while($row2=$select2->fetch(PDO::FETCH_OBJ))
                                                { ?>
                                                      <!-- NIVEAU 3 MENU PRODUITS -->

                                                      <li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="boutique.php?cat3=<?php echo $row2->cat_name;?>" style="padding: 10px 28px;"><?php echo $row2->cat_name;?></a>

                                                    </li>
                                                    
                                                      <!-- FIN NIVEAU 3 MENU PRODUITS -->
                                                      
                                                       <?php
                                                        }
                                                    ?>

                                                        </ul>
                                                      </div>


                      </li>
                      
                        <!-- FIN NIVEAU 3 MENU PRODUITS -->
                        
                         <?php
                          }
                      ?>

                        </ul>
                        
                          </div>

                          </li>  <!-- FIN NIVEAU 2 MENU PRODUITS -->

               
              <?php
                }
                ?>

                            

                
</ul>
</div>
</li>               <!-- FIN NIVEAU 1 MENU PRODUITS -->


<ul class="u-nav u-spacing-30 u-unstyled u-nav-1">
            <li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="Acceuil.html" style="padding: 10px 28px;">Fabricants</a>
</li>

<li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="panier.php" style="padding: 10px 28px;">Panier</a>
</li>
<!--<li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="inscription.php" style="padding: 10px 28px;">Inscription</a>
</li>-->

<li class="u-nav-item">
       <a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="connexion.php" style="padding: 10px 28px;">Connexion
       </a>
</li>

<li class="u-nav-item">

  

  <img src="img/BEVILECLOGO1.png" class="img-circle" alt="User Image">

                  <!--<p class="text-lowercase">-->
                    <?php echo $_SESSION['user_name']; ?> - <?php //echo $_SESSION['role']; ?>
                    <small class="text-capitalize"><?php echo $_SESSION['fullname']; ?></small>
                    <a href="../shop/misc/logout.php" class="btn btn-default btn-flat"
                    onclick="return confirm('Confirmer ?')"
                    class="btn btn-danger">Sign out</a>

                  <!--</p>-->

  

</li>
<!--<li class="u-nav-item"><a class="u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-grey-90 u-text-hover-grey-90" href="Contact.html" style="padding: 10px 28px;">Contact</a>
</li>
-->

</ul>
          </div>

                                        <!-- MENU HEADER FIN-->

          <div class="u-custom-menu u-nav-container-collapse">
            <div class="u-align-center u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
              <div class="u-sidenav-overflow">
                <div class="u-menu-close"></div>
                <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-3"><li class="u-nav-item"><a class="u-button-style u-nav-link" href="Acceuil.html" style="padding: 10px 28px;">Acceuil</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="About.html" style="padding: 10px 28px;">About</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="Contact.html" style="padding: 10px 28px;">Contact</a>
</li>

<li class="u-nav-item"><a class="u-button-style u-nav-link" style="padding: 10px 28px;">Magasin</a><div class="u-nav-popup"><ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-4"><li class="u-nav-item"><a class="u-button-style u-nav-link">category 2</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link">Category 1</a>
</li></ul>
</div>
</li>

</ul>
              </div>
            </div>
            <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
          </div>
        </nav>
      </div></header>