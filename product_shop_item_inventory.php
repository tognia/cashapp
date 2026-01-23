<?php
// product_shop_item_inventory.php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper" || $_SESSION['role'] == "Responsable") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
error_reporting(0);

// --- 1. RIGOROUS SHOP SELECTION LOGIC ---
if (isset($_POST['select_shop'])) {
    $_SESSION['select_shop'] = $_POST['shop'];
}

$shop = '';
if ($_SESSION['role'] == "Responsable") {
    $shop = $_SESSION['magasin'] ?? '';
} elseif ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
    $shop = $_SESSION['select_shop'] ?? '';
} else {
    $shop = $_SESSION['magasin'] ?? '';
}

if (!$shop && ($_SESSION['role'] == "Responsable" || $_SESSION['role'] == "Operator")) {
    echo '<script>swal("Attention", "Aucun magasin attribué.", "warning").then(() => { window.location.href = "index.php"; });</script>';
    exit();
}

$id = $_GET['id'] ?? null;

// --- 2. DELETE Logic (Shop Item) ---
if ($id && $shop && ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper")) {
    $delete = $pdo->prepare("DELETE FROM tbl_shop_item WHERE shop_code = :shop AND product_id = :id");
    $delete->bindParam(':shop', $shop);
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        echo '<script>jQuery(function(){ swal("Info", "Produit Supprimé du Magasin", "info").then(() => { window.location.href = "product_shop_item_inventory.php"; }); });</script>';
    }
}

// --- 3. MASS STOCK TRANSFER LOGIC ---
if (isset($_POST['mass_transfer_stock'])) {
    $transfers = $_POST['transfers'] ?? [];
    $shipment_date = $_POST['mass_shipment_date'] ?? date('Y-m-d');
    $notes = $_POST['mass_notes'] ?? '';
    $shop_code_target = $_POST['shop_code_target'] ?? '';
    $current_user_id = $_SESSION['user_id'] ?? null;
    $success_count = 0;

    if ($current_user_id && $shop_code_target && !empty($transfers)) {
        try {
            $pdo->beginTransaction();
            $update_main_stock = $pdo->prepare("UPDATE tbl_product SET stock = stock - :quantity WHERE product_code = :product_code");
            $insert_shipment = $pdo->prepare("INSERT INTO tbl_product_shipment (shipment_date, product_id, shipped_quantity, code_agence, user_id, delivery_status, notes, product_code, product_sku, product_name) VALUES (:date, :product_id, :quantity, :agence, :user_id, 'Delivered', :notes, :pcode, :psku, :pname)");

            foreach ($transfers as $transfer) {
                $p_id = $transfer['product_id'];
                $qty = $transfer['quantity'];
                $p_code = $transfer['product_code'];

                $check = $pdo->prepare("SELECT stock, product_sku, product_name FROM tbl_product WHERE product_code = :code");
                $check->execute([':code' => $p_code]);
                $main_p = $check->fetch(PDO::FETCH_ASSOC);

                if ($main_p && $main_p['stock'] >= $qty) {
                    $update_main_stock->execute([':quantity' => $qty, ':product_code' => $p_code]);
                    $insert_shipment->execute([
                        ':date' => $shipment_date,
                        ':product_id' => $p_id,
                        ':quantity' => $qty,
                        ':agence' => $shop_code_target,
                        ':user_id' => $current_user_id,
                        ':notes' => $notes,
                        ':pcode' => $p_code,
                        ':psku' => $main_p['product_sku'],
                        ':pname' => $main_p['product_name']
                    ]);
                    $success_count++;
                }
            }
            $pdo->commit();
            echo '<script>jQuery(function(){ swal("Succès", "' . $success_count . ' transferts effectués.", "success").then(() => { window.location.href="product_shop_item_inventory.php"; }); });</script>';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<script>jQuery(function(){ swal("Erreur", "Échec du transfert.", "error"); });</script>';
        }
    }
}

// --- 4. Database Query for Listing ---
$status = $_GET['status'] ?? 'all';
$statusName = "";
$delivered_subquery = "(SELECT COALESCE(SUM(tps.shipped_quantity), 0) FROM tbl_product_shipment tps WHERE tps.product_code = tsi.product_code AND tps.delivery_status = 'Delivered' AND tps.code_agence = :shop_param) AS total_delivered";

$sql = "SELECT tsi.*, $delivered_subquery FROM tbl_shop_item tsi WHERE tsi.shop_code = :shop_where ";
if ($status == 'ok') {
    $sql .= "AND tsi.stock > tsi.min_stock ";
    $statusName = " (STOCK OK)";
}
if ($status == 'alert') {
    $sql .= "AND tsi.stock <= tsi.min_stock AND tsi.stock <> 0 ";
    $statusName = " (ALERTE)";
}
if ($status == 'null') {
    $sql .= "AND tsi.stock = 0 ";
    $statusName = " (STOCK NULL)";
}
$sql .= "ORDER BY tsi.product_name ASC";

$select = $pdo->prepare($sql);
if ($shop) {
    $select->bindParam(':shop_param', $shop);
    $select->bindParam(':shop_where', $shop);
}

// Data for Dropdown and Agencies
$products_in_main = $pdo->query("SELECT product_code, product_name FROM tbl_product WHERE stock > 0 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$agencies_list = ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper") ? $pdo->query("SELECT code_agence, libelle_agence FROM agence")->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<div class="content-wrapper">
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Boutique : <b><?php echo htmlspecialchars($shop); ?></b> <?php echo $statusName; ?></h3>
                <div class="pull-right">

                    <?php if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper"): ?>
                        <form action="" method="POST" style="display:inline-block; margin-left:10px;">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <select name="shop" class="form-control" required>
                                    <?php foreach ($agencies_list as $row): ?>
                                        <option value="<?php echo $row['code_agence']; ?>" <?php echo ($row['code_agence'] == $shop) ? 'selected' : ''; ?>><?php echo $row['code_agence'] . ' ' . $row['libelle_agence']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-btn"><button type="submit" name="select_shop" class="btn btn-default btn-flat">SÉLECTIONNER</button></span>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>


        </div>

        <div class="box box-info" id="printableReport">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> Rapport des Expéditions vers : <b><?php echo htmlspecialchars($shop); ?></b></h3>
                <div class="pull-right no-print">
                    <button type="button" onclick="printReport()" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Imprimer Rapport</button>
                </div>
            </div>
            <div class="box-body">
                <form method="GET" action="product_shop_item_inventory.php" class="well no-print">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Date Début</label>
                            <input type="date" name="date_start" class="form-control" value="<?php echo $_GET['date_start'] ?? date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Date Fin</label>
                            <input type="date" name="date_end" class="form-control" value="<?php echo $_GET['date_end'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-info btn-block"><i class="fa fa-filter"></i> Filtrer</button>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label><br>
                            <div class="btn-group btn-block">
                                <a href="export_shipment_excel.php?shop=<?php echo $shop; ?>&start=<?php echo $_GET['date_start'] ?? ''; ?>&end=<?php echo $_GET['date_end'] ?? ''; ?>" class="btn btn-success" style="width:50%"><i class="fa fa-file-excel-o"></i> Excel</a>
                                <a href="export_shipment_pdf.php?shop=<?php echo $shop; ?>&start=<?php echo $_GET['date_start'] ?? ''; ?>&end=<?php echo $_GET['date_end'] ?? ''; ?>" class="btn btn-danger" style="width:50%"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="print-header visible-print" style="display:none; text-align:center; margin-bottom:20px;">
                    <h2>Rapport d'Expédition - Boutique: <?php echo $shop; ?></h2>
                    <p>Période du <?php echo $_GET['date_start'] ?? '...'; ?> au <?php echo $_GET['date_end'] ?? '...'; ?></p>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-gray">
                            <th>Code Produit</th>
                            <th>Nom du Produit</th>
                            <th>SKU</th>
                            <th class="text-center">Quantité Expédiée (Période)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $d_start = $_GET['date_start'] ?? null;
                        $d_end = $_GET['date_end'] ?? null;
                        if ($d_start && $d_end && $shop) {
                            $report_sql = "SELECT product_code, product_name, product_sku, SUM(shipped_quantity) as total_qty FROM tbl_product_shipment WHERE code_agence = :shop AND shipment_date BETWEEN :start AND :end GROUP BY product_code, product_name, product_sku ORDER BY total_qty DESC";
                            $stmt_report = $pdo->prepare($report_sql);
                            $stmt_report->execute([':shop' => $shop, ':start' => $d_start, ':end' => $d_end]);
                            if ($stmt_report->rowCount() > 0) {
                                while ($rep = $stmt_report->fetch(PDO::FETCH_OBJ)) {
                                    echo "<tr><td>{$rep->product_code}</td><td>{$rep->product_name}</td><td>{$rep->product_sku}</td><td class='text-center'><b>{$rep->total_qty}</b></td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Aucune donnée trouvée.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>Veuillez filtrer par date pour voir le rapport.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>


<style>
    @media print {

        .no-print,
        .main-footer,
        .main-header,
        .sidebar,
        .content-header,
        .box-success {
            display: none !important;
        }

        .content-wrapper {
            margin-left: 0 !important;
        }

        .visible-print {
            display: block !important;
        }

        .box-info {
            border: none !important;
        }
    }
</style>

<script>
    function printReport() {
        window.print();
    }

    function openPrintWindow(status) {
        var url = 'print_shop_item_list.php?status=' + status;
        window.open(url, 'PrintShopList', 'width=900,height=700');
    }

    $(document).ready(function() {
        if ($.fn.DataTable) $('#myProduct').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
        if ($.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('#massTransferModal'),
                placeholder: "-- Chercher un produit --",
                allowClear: true
            });
        }
        let line_idx = 0;

        function addLine(code) {
            if (!code) return;
            if ($(`.pcode-check[value="${code}"]`).length > 0) {
                swal("Attention", "Déjà dans la liste", "warning");
                return;
            }
            $.getJSON('fetch_product_main_and_shop.php', {
                code: code,
                shop: '<?php echo $shop; ?>'
            }, function(res) {
                if (res.status === 'success') {
                    line_idx++;
                    let row = `<tr><td><b>${res.data.product_name}</b><br><small>${res.data.product_code}</small><input type="hidden" name="transfers[${line_idx}][product_id]" value="${res.data.product_id}"><input type="hidden" name="transfers[${line_idx}][product_code]" value="${res.data.product_code}"><input type="hidden" class="pcode-check" value="${res.data.product_code}"></td><td class="text-center"><span class="label label-success">${res.data.main_stock}</span></td><td class="text-center"><span class="label label-info">${res.data.shop_stock}</span></td><td><input type="number" name="transfers[${line_idx}][quantity]" class="form-control" value="1" min="1" max="${res.data.main_stock}"></td><td class="text-center"><button type="button" class="btn btn-danger btn-xs remove-line"><i class="fa fa-times"></i></button></td></tr>`;
                    $('#transfer_lines_container').append(row);
                    $('#no-transfer-row').hide();
                    $('#submit_mass_transfer').prop('disabled', false);
                    $('#product_code_input_transfer').val('').focus();
                    $('#product_select_dropdown_transfer').val(null).trigger('change');
                } else {
                    swal("Erreur", res.message, "error");
                }
            });
        }
        $('#product_select_dropdown_transfer').on('select2:select', function(e) {
            addLine(e.params.data.id);
        });
        $('#add_transfer_line_btn').on('click', function() {
            let code = $('#product_code_input_transfer').val().trim();
            if (code !== "") addLine(code);
        });
        $('#product_code_input_transfer').keypress(e => {
            if (e.which == 13) {
                e.preventDefault();
                addLine($(e.target).val().trim());
            }
        });
        $(document).on('click', '.remove-line', function() {
            $(this).closest('tr').remove();
            if ($('#transfer_lines_container tr').length <= 1) {
                $('#no-transfer-row').show();
                $('#submit_mass_transfer').prop('disabled', true);
            }
        });
    });

    function confirmMassTransfer() {
        return confirm("Confirmer l'expédition ?");
    }
</script>

<?php include_once 'inc/footer_all.php'; ?>