<?php
// order.php
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

error_reporting(0);
$id = $_GET['id'] ?? null;
$today_date = date("Y-m-d");
$magasin = $_SESSION['magasin'] ?? '';

// --- LOGIQUE D'ANNULATION ---
if ($id) {
    try {
        $pdo->beginTransaction();
        $stmt_inv = $pdo->prepare("SELECT user, status FROM tbl_invoice WHERE invoice_id = :id");
        $stmt_inv->execute([':id' => $id]);
        $invoice = $stmt_inv->fetch(PDO::FETCH_ASSOC);

        if ($invoice && $invoice['status'] == 'saved') {
            $stmt_user = $pdo->prepare("SELECT magasin FROM tbl_user WHERE username = :user");
            $stmt_user->execute([':user' => $invoice['user']]);
            $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
            $shop_code = $user_data['magasin'] ?? '';

            if (!empty($shop_code)) {
                $stmt_details = $pdo->prepare("SELECT product_id, qty FROM tbl_invoice_detail WHERE invoice_id = :id");
                $stmt_details->execute([':id' => $id]);
                while ($item = $stmt_details->fetch(PDO::FETCH_ASSOC)) {
                    $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock + :qty WHERE product_id = :pid AND shop_code = :shop");
                    $update_stock->execute([':qty' => $item['qty'], ':pid' => $item['product_id'], ':shop' => $shop_code]);
                }
            }
            $update = $pdo->prepare("UPDATE tbl_invoice SET status = 'canceled' WHERE invoice_id = :id");
            $update->execute([':id' => $id]);
            $pdo->commit();
            echo '<script>jQuery(function(){ swal("Succès", "Transaction annulée", "success"); });</script>';
        }
    } catch (Exception $e) {
        $pdo->rollback();
    }
}

$export_params = http_build_query($_REQUEST);
$view_status = $_REQUEST['view_status'] ?? 'saved';
include("include/stat_op_caisse.php");
?>

<section class="content container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Transactions : <?php echo ($view_status == 'saved') ? 'VALIDÉES' : 'ANNULÉES'; ?></h3>
            <div class="pull-right">
                <a href="order.php?view_status=saved" class="btn btn-sm btn-default">Validées</a>
                <a href="order.php?view_status=canceled" class="btn btn-sm btn-default">Annulées</a>
                <a href="create_order.php" class="btn btn-info btn-sm nav-link">Nouvelle Transaction</a>
            </div>
        </div>

        <div class="box-body">
            <table class="table table-striped" id="myOrder">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Facture</th>
                        <th>Opérateur</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $conditions = [];
                    $params = [];

                    // --- Base Query ---
                    $sql = "SELECT * FROM tbl_invoice";

                    // --- 1. Filter by Status (Always present) ---
                    $conditions[] = "status = :status";
                    $params[':status'] = $view_status;

                    // --- 2. Filter by Date Range ---
                    // Check both POST and GET to ensure filtering persists
                    $date1 = $_REQUEST['date_1'] ?? '';
                    $date2 = $_REQUEST['date_2'] ?? '';

                    if (!empty($date1) && !empty($date2)) {
                        $conditions[] = "order_date BETWEEN :fromdate AND :todate";
                        $params[':fromdate'] = $date1;
                        $params[':todate'] = $date2;
                    }

                    // --- 3. Filter by Magasin (Security/Role check) ---
                    if ($_SESSION['role'] == "Admin") {
                        $leshop = $_REQUEST['shop'] ?? 'all';
                        if ($leshop != "all") {
                            $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
                            $params[':leshop'] = $leshop;
                        }
                    } else {
                        // Non-admins only see their own store's data
                        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                        $params[':magasin'] = $magasin;
                    }

                    // --- 4. Filter by Operator ---
                    $op_filter = $_REQUEST['operator_filter'] ?? 'all';
                    if ($op_filter != 'all') {
                        $conditions[] = "user = :op_user";
                        $params[':op_user'] = $op_filter;
                    }

                    // --- Build Final SQL ---
                    if (!empty($conditions)) {
                        $sql .= " WHERE " . implode(" AND ", $conditions);
                    }

                    $sql .= " ORDER BY invoice_id DESC";
                    // We removed the LIMIT so the date filter can scan the entire history

                    $select = $pdo->prepare($sql);
                    $select->execute($params);

                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                        echo '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row->invoice_id . '</td>
            <td class="text-uppercase">' . $row->cashier_name . '</td>
            <td>' . date("d-m-Y", strtotime($row->order_date)) . '</td>
            <td><b>' . number_format($row->total) . '</b> <small>FCFA</small></td>
            <td>' . $row->payment_mode . '</td>
            <td>
                <button type="button" class="btn btn-primary btn-sm btn-view" data-id="' . $row->invoice_id . '" title="Voir Détails">
                    <i class="fa fa-eye"></i>
                </button>
                <button type="button" onclick="openDesktopReceiptWindow(' . $row->invoice_id . ')" class="btn btn-info btn-sm" title="Imprimer">
                    <i class="fa fa-print"></i>
                </button>';

                        // Cancellation logic (Only for Admin or Responsable on today's date)
                        if ($view_status == 'saved' && ($_SESSION['role'] == "Admin" || ($_SESSION['role'] == "Responsable" && $row->order_date == $today_date))) {
                            echo ' <a href="order.php?id=' . $row->invoice_id . '&view_status=saved" 
                           onclick="return confirm(\'Annuler cette transaction ?\')" 
                           class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                        }
                        echo '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-details" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Détails de la Facture</h4>
            </div>
            <div class="modal-body" id="details-body">
            </div>
        </div>
    </div>
</div>

<?php include_once 'inc/footer_all.php'; ?>

<script>
    $(document).ready(function() {
        // 1. FAST NAVIGATION: Stop page work when clicking links
        $('.nav-link, a').on('click', function() {
            if (!$(this).hasClass('dropdown-toggle')) {
                window.stop(); // Stops the browser from finishing the current page render
            }
        });

        // 2. DATATABLE OPTIMIZATION
        $('#myOrder').DataTable({
            "order": [
                [1, "desc"]
            ],
            "deferRender": true // Only renders rows when they come into view
        });

        // 3. AJAX MODAL LOAD (No more N+1 queries)
        $('.btn-view').on('click', function() {
            var invId = $(this).data('id');
            $('#details-body').html('<div class="text-center"><i class="fa fa-refresh fa-spin"></i> Chargement...</div>');
            $('#modal-details').modal('show');
            $('#details-body').load('fetch_details.php?id=' + invId);
        });
    });

    function openDesktopReceiptWindow(invoiceId) {
        window.open('print_receipt.php?id=' + invoiceId, 'Receipt', 'width=450,height=600');
    }
</script>