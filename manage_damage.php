<?php
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

include_once ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") ? 'inc/header_all.php' : 'inc/header_all_operator.php';

// --- Logic for Filtering ---
$shop_filter = $_POST['select_shop'] ?? '';
$date_start = $_POST['date_start'] ?? date('Y-m-01'); // Default to start of month
$date_end = $_POST['date_end'] ?? date('Y-m-d');

// SQL Query based on role and filter
$query = "SELECT * FROM tbl_product_damage WHERE damage_date BETWEEN :start AND :end";

if ($_SESSION['role'] == "Responsable") {
    $query .= " AND code_agence = :agence";
    $shop_filter = $_SESSION['magasin'];
} elseif (!empty($shop_filter)) {
    $query .= " AND code_agence = :agence";
}

$query .= " ORDER BY damage_date DESC";
$select = $pdo->prepare($query);
$select->bindParam(':start', $date_start);
$select->bindParam(':end', $date_end);
if (!empty($shop_filter)) {
    $select->bindParam(':agence', $shop_filter);
}
$select->execute();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Rapport des Dommages <small>Gestion et Impression</small></h1>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary no-print">
            <div class="box-header with-border">
                <h3 class="box-title">Filtres de recherche</h3>
            </div>
            <div class="box-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Du:</label>
                                <input type="date" name="date_start" class="form-control" value="<?php echo $date_start; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Au:</label>
                                <input type="date" name="date_end" class="form-control" value="<?php echo $date_end; ?>">
                            </div>
                        </div>
                        <?php if ($_SESSION['role'] == "Admin") { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Boutique:</label>
                                    <select name="select_shop" class="form-control">
                                        <option value="">Toutes les boutiques</option>
                                        <?php
                                        $shops = $pdo->prepare("SELECT DISTINCT shop_code FROM tbl_shop_item");
                                        $shops->execute();
                                        while ($s = $shops->fetch(PDO::FETCH_OBJ)) {
                                            $selected = ($shop_filter == $s->shop_code) ? 'selected' : '';
                                            echo "<option value='" . $s->shop_code . "' $selected>" . $s->shop_code . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> Filtrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-danger" id="printSection">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des pertes: <b><?php echo $shop_filter ?: 'Toutes Agences'; ?></b></h3>
                <button class="btn btn-success pull-right no-print" onclick="window.print();">
                    <i class="fa fa-print"></i> Imprimer le Rapport
                </button>
            </div>
            <div class="box-body">
                <table class="table table-striped" id="damageTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Type</th>
                            <th>Agence</th>
                            <th>Agent</th>
                            <th class="no-print">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_qty = 0;
                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            $total_qty += $row->damaged_quantity;
                        ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row->damage_date)); ?></td>
                                <td><?php echo $row->product_code; ?></td>
                                <td><?php echo $row->product_name; ?></td>
                                <td><span class="label label-danger"><?php echo $row->damaged_quantity; ?></span></td>
                                <td><?php echo $row->damage_type; ?></td>
                                <td><?php echo $row->code_agence; ?></td>
                                <td><?php echo $row->user_id; ?></td>
                                <td class="no-print"><small><?php echo $row->notes; ?></small></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #f9f9f9; font-weight: bold;">
                            <td colspan="3" class="text-right">TOTAL QUANTITÉ PERDUE:</td>
                            <td><?php echo $total_qty; ?></td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        .content-wrapper {
            margin-left: 0 !important;
        }

        .main-footer,
        .main-header {
            display: none !important;
        }

        #printSection {
            border: none !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        th,
        td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }
</style>

<?php include_once 'inc/footer_all.php'; ?>