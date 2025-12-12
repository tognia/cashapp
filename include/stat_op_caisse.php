<div class="content-wrapper">
  <section class="content-header">
    <h1>Transactions</h1>
    <hr>
  </section>

  <?php
  // Récupération des paramètres globaux
  $today = date("Y-m-d");
  $view_status = $_GET['view_status'] ?? 'saved';
  $user_role = $_SESSION['role'] ?? '';
  $user_magasin = $_SESSION['magasin'] ?? '';
  $current_user = $_SESSION['user_name'] ?? '';

  // Couleurs dynamiques
  $box_color = ($view_status == 'canceled') ? 'bg-red' : 'bg-green';
  $text_status = ($view_status == 'canceled') ? '(Annulées)' : '(Validées)';
  ?>

  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST" autocomplete="off">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo isset($_POST['date_filter']) ? "Du : " . $_POST['date_1'] : ""; ?></h3>
          <h3 class="box-title"><?php echo isset($_POST['date_filter']) ? "Au : " . $_POST['date_2'] : ""; ?></h3>
        </div>

        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Date Début</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1"
                    value="<?php echo isset($_POST['date_filter']) ? $_POST['date_1'] : $today; ?>">
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Date Fin</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2"
                    value="<?php echo isset($_POST['date_filter']) ? $_POST['date_2'] : $today; ?>">
                </div>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Magasin</label>
                <?php if ($user_role == "Admin") { ?>
                  <select class="form-control" name="shop" onchange="this.form.submit()">
                    <option value="all" <?php echo (isset($_POST['shop']) && $_POST['shop'] == 'all') ? 'selected' : ''; ?>>Tous</option>
                    <?php
                    $select1 = $pdo->prepare("SELECT * FROM agence");
                    $select1->execute();
                    while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                      $selected = (isset($_POST['shop']) && $_POST['shop'] == $row['code_agence']) ? 'selected' : '';
                      echo '<option value="' . $row['code_agence'] . '" ' . $selected . '>' . $row['code_agence'] . '</option>';
                    }
                    ?>
                  </select>
                <?php } else { ?>
                  <input type="text" class="form-control" value="<?php echo $user_magasin; ?>" disabled>
                  <input type="hidden" name="shop" value="<?php echo $user_magasin; ?>">
                <?php } ?>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Opérateur</label>
                <select class="form-control" name="operator_filter">
                  <option value="all">-- Tous --</option>

                  <?php
                  if ($user_role == "Operator") {
                    // Pour l'opérateur : Choix simple
                    $sel_me = (isset($_POST['operator_filter']) && $_POST['operator_filter'] == $current_user) ? 'selected' : '';
                    echo '<option value="' . $current_user . '" ' . $sel_me . '>Mes Opérations</option>';
                  } else {
                    // Pour Admin/Responsable : Liste des utilisateurs selon le shop sélectionné
                    $sql_users = "SELECT username, fullname FROM tbl_user";

                    // Si un shop spécifique est sélectionné (et n'est pas 'all')
                    $selected_shop = $_POST['shop'] ?? 'all';

                    // Si Responsable, forcer son shop
                    if ($user_role == "Responsable") {
                      $selected_shop = $user_magasin;
                    }

                    if ($selected_shop != 'all') {
                      $sql_users .= " WHERE magasin = '$selected_shop'";
                    }

                    $stmt_users = $pdo->prepare($sql_users);
                    $stmt_users->execute();
                    while ($u = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
                      $u_val = $u['username'];
                      $u_name = $u['fullname'];
                      $sel_op = (isset($_POST['operator_filter']) && $_POST['operator_filter'] == $u_val) ? 'selected' : '';
                      echo '<option value="' . $u_val . '" ' . $sel_op . '>' . $u_name . '</option>';
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <label>&nbsp;</label>
              <input type="submit" name="date_filter" value="Afficher" class="btn btn-success btn-block">
            </div>
          </div>

          <?php
          // --- CONSTRUCTION DYNAMIQUE DE LA CLAUSE WHERE (POUR STATS ET GRAPHIQUES) ---
          $where_conditions = [];
          $params = [];

          $where_conditions[] = "status = :status";
          $params[':status'] = $view_status;

          if (isset($_POST['date_filter'])) {
            $date1 = $_POST['date_1'];
            $date2 = $_POST['date_2'];
            $where_conditions[] = "order_date BETWEEN :d1 AND :d2";
            $params[':d1'] = $date1;
            $params[':d2'] = $date2;
          }

          // Filtre Magasin
          $shop_filter = $_POST['shop'] ?? 'all';
          // Si Admin et shop specifique, ou si non-admin (forcé à son shop)
          if ($user_role != "Admin" || ($user_role == "Admin" && $shop_filter != "all")) {
            // Exception: Si Admin a choisi 'all', on ne filtre pas le magasin
            $target_shop = ($user_role == "Admin") ? $shop_filter : $user_magasin;

            // Note: La logique initiale utilisait une sous-requête sur user. 
            // Nous gardons cette logique pour cohérence, sauf si on filtre par opérateur précis.
            $where_conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :shop)";
            $params[':shop'] = $target_shop;
          }

          // NOUVEAU: Filtre Opérateur
          $op_filter = $_POST['operator_filter'] ?? 'all';
          if ($op_filter != 'all') {
            $where_conditions[] = "user = :op_user";
            $params[':op_user'] = $op_filter;
          }

          $where_sql = "";
          if (count($where_conditions) > 0) {
            $where_sql = "WHERE " . implode(" AND ", $where_conditions);
          }

          // ... (Le reste du code des requêtes SQL Totaux/Charts reste identique car il utilise $where_sql) ...

          // --- REQUÊTE 1 : TOTAUX ---
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
            <div class="col-md-5 col-xs-12">
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
          $sql_chart = "SELECT order_date, sum(total) as price FROM tbl_invoice $where_sql GROUP BY order_date";
          $stmt_chart = $pdo->prepare($sql_chart);
          $stmt_chart->execute($params);
          $chart_total = [];
          $chart_date = [];
          while ($row = $stmt_chart->fetch(PDO::FETCH_ASSOC)) {
            $chart_total[] = $row['price'];
            $chart_date[] = $row['order_date'];
          }
          $total = $chart_total;
          $date = $chart_date;

          // Best Sellers (Fix ambiguity on order_date)
          $where_sql_detail = str_replace("order_date", "i.order_date", $where_sql);
          // Also need to handle 'user' ambiguity if detail has user (usually it doesn't, but 'status' works)
          // Ideally prefix all where clause fields with 'i.' but keeping it simple:

          $sql_best = "SELECT d.product_name, sum(d.qty) as q 
                       FROM tbl_invoice_detail d
                       JOIN tbl_invoice i ON d.invoice_id = i.invoice_id
                       $where_sql_detail 
                       GROUP BY d.product_id 
                       ORDER BY q DESC LIMIT 10";
          $stmt_best = $pdo->prepare($sql_best);
          $stmt_best->execute($params);
          $bs_pname = [];
          $bs_qty = [];
          while ($row = $stmt_best->fetch(PDO::FETCH_ASSOC)) {
            $bs_pname[] = $row['product_name'];
            $bs_qty[] = $row['q'];
          }
          $pname = $bs_pname;
          $qty = $bs_qty;
          ?>

          <div class="chart"><canvas id="myChart" style="height:10px;"></canvas></div>
          <div class="chart"><canvas id="myBestSellItem" style="height:20px;"></canvas></div>
        </div>
      </form>