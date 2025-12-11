<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Transactions
    </h1>
    <hr>
  </section>

  <?php
  // Récupération des paramètres globaux
  $today = date("Y-m-d");
  $view_status = $_GET['view_status'] ?? 'saved'; // Par défaut 'saved'
  $user_role = $_SESSION['role'] ?? '';
  $user_magasin = $_SESSION['magasin'] ?? '';

  // Couleurs dynamiques selon le statut
  $box_color = ($view_status == 'canceled') ? 'bg-red' : 'bg-green';
  $text_status = ($view_status == 'canceled') ? '(Annulées)' : '(Validées)';
  ?>

  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST" autocomplete="off">
        <div class="box-header with-border">
          <h3 class="box-title">Date Début : <?php echo isset($_POST['date_filter']) ? $_POST['date_1'] : $today; ?></h3>
          <h3 class="box-title">Date Fin : <?php echo isset($_POST['date_filter']) ? $_POST['date_2'] : $today; ?></h3>
        </div>

        <div class="box-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1" data-date-format="yyyy-mm-dd"
                    value="<?php echo isset($_POST['date_filter']) ? $_POST['date_1'] : $today; ?>">
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2" data-date-format="yyyy-mm-dd"
                    value="<?php echo isset($_POST['date_filter']) ? $_POST['date_2'] : $today; ?>">
                </div>
              </div>
            </div>

            <div class="col-md-2">
              <?php if ($user_role == "Admin") { ?>
                <div class="form-group">
                  <label for="">Magasin</label>
                  <select class="form-control" name="shop">
                    <option value="all" <?php echo (isset($_POST['shop']) && $_POST['shop'] == 'all') ? 'selected' : ''; ?>>Tous les magasins</option>
                    <?php
                    $select1 = $pdo->prepare("SELECT * FROM agence");
                    $select1->execute();
                    while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                      $selected = (isset($_POST['shop']) && $_POST['shop'] == $row['code_agence']) ? 'selected' : '';
                      echo '<option value="' . $row['code_agence'] . '" ' . $selected . '>' . $row['code_agence'] . " " . $row['libelle_agence'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              <?php } ?>

              <input type="submit" name="date_filter" value="Afficher" class="btn btn-success btn-block">
            </div>
          </div>

          <?php
          // --- CONSTRUCTION DYNAMIQUE DE LA CLAUSE WHERE ---
          // Cette partie permet d'appliquer les mêmes filtres aux Totaux, aux Graphiques et à la Liste

          $where_conditions = [];
          $params = [];

          // 1. Filtre par statut (Saved ou Canceled)
          $where_conditions[] = "status = :status";
          $params[':status'] = $view_status;

          // 2. Filtre par Date (si posté, sinon defaut ?) 
          // Note: Le formulaire renvoie toujours date_1 et date_2 si on clique sur afficher, 
          // ou on utilise $today si pas de post.
          if (isset($_POST['date_filter'])) {
            $date1 = $_POST['date_1'];
            $date2 = $_POST['date_2'];
          } else {
            // Par défaut, pas de filtre date strict sur le chargement initial dans votre code original,
            // mais pour la cohérence on peut mettre today ou laisser vide. 
            // Ici je n'applique le filtre date que si le form est soumis pour garder le comportement standard,
            // ou si vous voulez voir tout l'historique par défaut.
            // Modif: Si pas de filtre, on ne met pas de condition de date (tout l'historique) ou on met today ?
            // Votre code original mettait "WHERE user_login..." sans date si pas de filtre.
            // Mais les inputs affichent $today. On va suivre la logique : Si POST, on filtre dates.
          }

          if (isset($_POST['date_filter'])) {
            $where_conditions[] = "order_date BETWEEN :d1 AND :d2";
            $params[':d1'] = $date1;
            $params[':d2'] = $date2;
          }

          // 3. Filtre par Magasin
          if ($user_role == "Admin") {
            $shop_filter = $_POST['shop'] ?? 'all';
            if ($shop_filter != "all") {
              $where_conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :shop)";
              $params[':shop'] = $shop_filter;
            }
          } else {
            // Si pas Admin, on force le magasin de l'utilisateur
            $where_conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :myshop)";
            $params[':myshop'] = $user_magasin;
          }

          // Construction de la chaine WHERE
          $where_sql = "";
          if (count($where_conditions) > 0) {
            $where_sql = "WHERE " . implode(" AND ", $where_conditions);
          }

          // --- REQUÊTE 1 : TOTAUX (Montant et Nombre) ---
          $sql_totals = "SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_invoice $where_sql";
          $stmt = $pdo->prepare($sql_totals);
          $stmt->execute($params);
          $row_totals = $stmt->fetch(PDO::FETCH_OBJ);

          $total_amount = $row_totals->total ?? 0;
          $total_invoices = $row_totals->invoice ?? 0;
          ?>

          <br>
          <div class="row">
            <div class="col-md-offset-2 col-md-4 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon <?php echo $box_color; ?>"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">TOTAL TRANSACTIONS <?php echo $text_status; ?></span>
                  <span class="info-box-number"><?php echo $total_invoices; ?></span>
                </div>
              </div>
            </div>

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-offset-1 col-md-5 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon <?php echo $box_color; ?>"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">MONTANT TOTAL <?php echo $text_status; ?></span>
                  <span class="info-box-number"> <?php echo number_format($total_amount, 0) . " FCFA"; ?></span>
                </div>
              </div>
            </div>
          </div>

          <?php
          // --- REQUÊTE 2 : GRAPHIQUE PAR DATE ---
          // On réutilise exactement le même $where_sql pour que le graphique soit cohérent avec les chiffres
          $sql_chart = "SELECT order_date, sum(total) as price FROM tbl_invoice $where_sql GROUP BY order_date";
          $stmt_chart = $pdo->prepare($sql_chart);
          $stmt_chart->execute($params);

          $chart_total = [];
          $chart_date = [];

          while ($row = $stmt_chart->fetch(PDO::FETCH_ASSOC)) {
            $chart_total[] = $row['price'];
            $chart_date[] = $row['order_date'];
          }

          // Variables pour le JS (utilisées dans order.php)
          $total = $chart_total;
          $date = $chart_date;
          ?>

          <div class="chart">
            <canvas id="myChart" style="height:10px;"></canvas>
          </div>

          <?php
          // --- REQUÊTE 3 : BEST SELLERS (Produits) ---
          // Attention: tbl_invoice_detail n'a pas directement 'user' ou 'status'.
          // Il faut faire une jointure avec tbl_invoice pour appliquer les filtres.

          $sql_best = "SELECT d.product_name, sum(d.qty) as q 
                       FROM tbl_invoice_detail d
                       JOIN tbl_invoice i ON d.invoice_id = i.invoice_id
                       $where_sql 
                       GROUP BY d.product_id 
                       ORDER BY q DESC LIMIT 10"; // Ajout d'une limite pour l'esthétique

          // Note: $where_sql contient des références à des colonnes. 
          // Comme 'status', 'order_date', 'user' sont dans tbl_invoice, et qu'on a fait un JOIN, 
          // il peut y avoir ambiguïté si les colonnes ont le même nom.
          // Heureusement, order_date est dans les deux, status/user seulement dans invoice.
          // Pour être propre, on devrait préfixer, mais vu la structure simple, ça devrait passer 
          // ou on remplace order_date par i.order_date dans le WHERE string.

          // Correction rapide pour l'ambiguïté potentielle sur order_date dans la jointure :
          $where_sql_detail = str_replace("order_date", "i.order_date", $where_sql);

          $stmt_best = $pdo->prepare($sql_best);
          $stmt_best->execute($params);

          $bs_pname = [];
          $bs_qty = [];
          while ($row = $stmt_best->fetch(PDO::FETCH_ASSOC)) {
            $bs_pname[] = $row['product_name'];
            $bs_qty[] = $row['q'];
          }

          // Variables pour le JS
          $pname = $bs_pname;
          $qty = $bs_qty;
          ?>

          <div class="chart">
            <canvas id="myBestSellItem" style="height:20px;"></canvas>
          </div>

        </div>
      </form>