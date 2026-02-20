<?php
// order.php
include_once 'db/connect_db.php';

// 1. Session & Access Control
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
$view_status = $_REQUEST['view_status'] ?? 'saved';
$is_load_all = isset($_GET['load_all']) && $_GET['load_all'] == 1;

// --- LOGIQUE D'ANNULATION (Simplified for clarity) ---
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
            echo '<script>jQuery(function(){ swal("Info", "Transaction annulée.", "success"); });</script>';
        }
    } catch (Exception $e) {
        $pdo->rollback();
    }
}

// Prepare export parameters for PDF/Excel/Print
$export_params = http_build_query($_REQUEST);
include("include/stat_op_caisse.php");
?>

<section class="content container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Transactions :
                <?php echo ($view_status == 'saved') ? '<span class="label label-success">VALIDÉES</span>' : '<span class="label label-danger">ANNULÉES</span>'; ?>
            </h3>

            <div class="pull-right">
                <div class="btn-group" style="margin-right: 10px;">
                    <a href="order.php?view_status=saved" class="btn btn-sm <?php echo ($view_status == 'saved') ? 'btn-success active' : 'btn-default'; ?>">Validées</a>
                    <a href="order.php?view_status=canceled" class="btn btn-sm <?php echo ($view_status == 'canceled') ? 'btn-danger active' : 'btn-default'; ?>">Annulées</a>
                </div>

                <button onclick="openDesktopExportWindow('print_current_view.php', '<?php echo $export_params; ?>', 'PrintListView')" class="btn btn-warning btn-sm" title="Imprimer">
                    <i class="fa fa-print"></i>
                </button>

                <button type="button" onclick="openDesktopExportWindow('export_pdf.php', '<?php echo $export_params; ?>', 'SalesPDF')" class="btn btn-primary btn-sm" title="PDF">
                    <i class="fa fa-file-pdf-o"></i>
                </button>

                <button type="button" onclick="downloadFile('export_excel.php', '<?php echo $export_params; ?>')" class="btn btn-success btn-sm" title="Excel">
                    <i class="fa fa-file-excel-o"></i>
                </button>

                <?php if (!$is_load_all): ?>
                    <a href="order.php?<?php echo http_build_query(array_merge($_GET, ['load_all' => 1])); ?>" class="btn btn-danger btn-sm" style="margin-left:5px;">
                        <i class="fa fa-database"></i> Charger TOUT
                    </a>
                <?php else: ?>
                    <a href="order.php?<?php echo http_build_query(array_merge($_GET, ['load_all' => 0])); ?>" class="btn btn-default btn-sm" style="margin-left:5px;">
                        <i class="fa fa-bolt"></i> Mode Rapide
                    </a>
                <?php endif; ?>

                <a href="create_order.php" class="btn btn-info btn-sm nav-link" style="margin-left:10px;">Nouvelle Transaction</a>
            </div>
        </div>

        <div class="box-body">
            <div style="overflow-x:auto;">
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
                        $conditions = ["status = :status"];
                        $params = [':status' => $view_status];

                        // Restore Date Filters logic
                        $date1 = $_REQUEST['date_1'] ?? '';
                        $date2 = $_REQUEST['date_2'] ?? '';
                        if (!empty($date1) && !empty($date2)) {
                            $conditions[] = "order_date BETWEEN :d1 AND :d2";
                            $params[':d1'] = $date1;
                            $params[':d2'] = $date2;
                        }

                        // Restore Shop Filter logic
                        if ($_SESSION['role'] == "Admin") {
                            $leshop = $_REQUEST['shop'] ?? 'all';
                            if ($leshop != "all") {
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
                                $params[':leshop'] = $leshop;
                            }
                        } else {
                            $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                            $params[':magasin'] = $magasin;
                        }

                        $sql = "SELECT * FROM tbl_invoice WHERE " . implode(" AND ", $conditions) . " ORDER BY invoice_id DESC";
                        if (!$is_load_all) {
                            $sql .= " LIMIT 1000";
                        }

                        $select = $pdo->prepare($sql);
                        $select->execute($params);
                        $no = 1;

                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row->invoice_id; ?></td>
                                <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($row->order_date)); ?></td>
                                <td><b><?php echo number_format($row->total); ?></b> <small>FCFA</small></td>
                                <td><?php echo $row->payment_mode; ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-view" data-id="<?php echo $row->invoice_id; ?>">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" onclick="openDesktopReceiptWindow(<?php echo $row->invoice_id; ?>)" class="btn btn-info btn-sm">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <?php if ($view_status == 'saved' && ($_SESSION['role'] == "Admin" || ($_SESSION['role'] == "Responsable" && $row->order_date == $today_date))): ?>
                                        <a href="order.php?id=<?php echo $row->invoice_id; ?>&view_status=saved" onclick="return confirm('Annuler?')" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-details" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Détails Facture N° <b id="display_inv_id"></b></h4>
            </div>
            <div class="modal-body" id="details-body"></div>
        </div>
    </div>
</div>

<?php include_once 'inc/footer_all.php'; ?>

<script>
    $(document).ready(function() {
        // 1. FAST NAVIGATION Logic
        $('.nav-link, a.btn-info').on('click', function() {
            window.stop();
        });

        // 2. DATATABLE
        $('#myOrder').DataTable({
            "order": [
                [1, "desc"]
            ],
            "pageLength": 25,
            "deferRender": true
        });

        // 3. AJAX MODAL
        $('.btn-view').on('click', function() {
            var invId = $(this).data('id');
            $('#display_inv_id').text(invId);
            $('#details-body').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-2x"></i></div>');
            $('#modal-details').modal('show');
            $('#details-body').load('fetch_details.php?id=' + invId);
        });
    });

    // 4. RESTORED HELPER FUNCTIONS FOR EXPORT
    function openDesktopExportWindow(page, params, windowName) {
        var url = page + '?' + params;
        var features = 'width=1000,height=700,scrollbars=yes,resizable=yes';
        var newWindow = window.open(url, windowName, features);
        if (page === 'print_current_view.php') {
            newWindow.onload = function() {
                newWindow.print();
            };
        }
    }

    function downloadFile(page, params) {
        var url = page + '?' + params;
        var link = document.createElement('a');
        link.href = url;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function openDesktopReceiptWindow(invoiceId) {
        window.open('print_receipt.php?id=' + invoiceId, 'Receipt', 'width=450,height=600');
    }
</script>