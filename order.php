<?php
// order.php

include_once 'db/connect_db.php';

// Vérification de la session utilisateur
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    // Inclure le header approprié en fonction du rôle
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

// Désactiver l'affichage des erreurs PHP (déconseillé en production)
error_reporting(0);

// Récupération sécurisée de l'ID pour la suppression
$id = $_GET['id'] ?? null;

// Définition de la date du jour pour la restriction de suppression par Responsable
$today_date = date("Y-m-d");
$magasin = $_SESSION['magasin'] ?? ''; // Magasin de l'utilisateur actuel

// --- 2 & 3. LOGIQUE D'ANNULATION (SOFT DELETE) ET RETOUR DE STOCK ---
if ($id) {
    try {
        $pdo->beginTransaction();

        // 1. Récupérer les infos de la facture (pour vérifier le statut et l'utilisateur)
        $stmt_inv = $pdo->prepare("SELECT user, status, invoice_id FROM tbl_invoice WHERE invoice_id = :id");
        $stmt_inv->execute([':id' => $id]);
        $invoice = $stmt_inv->fetch(PDO::FETCH_ASSOC);

        // On procède seulement si la facture existe et n'est pas déjà annulée
        if ($invoice && $invoice['status'] == 'saved') {

            // 2. Identifier le magasin d'origine de la commande
            // On cherche le magasin de l'utilisateur qui a créé la commande
            $stmt_user = $pdo->prepare("SELECT magasin FROM tbl_user WHERE username = :user");
            $stmt_user->execute([':user' => $invoice['user']]);
            $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

            $shop_code = $user_data['magasin'] ?? '';

            if (!empty($shop_code)) {
                // 3. Récupérer les produits de la facture pour remettre en stock
                $stmt_details = $pdo->prepare("SELECT product_id, qty FROM tbl_invoice_detail WHERE invoice_id = :id");
                $stmt_details->execute([':id' => $id]);

                while ($item = $stmt_details->fetch(PDO::FETCH_ASSOC)) {
                    // Mise à jour du stock : On AJOUTE (+ qty) car on annule la vente
                    $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock + :qty WHERE product_id = :pid AND shop_code = :shop");
                    $update_stock->execute([
                        ':qty' => $item['qty'],
                        ':pid' => $item['product_id'],
                        ':shop' => $shop_code
                    ]);
                }
            }

            // 4. Mettre à jour le statut de la facture à "canceled"
            $cancel_query = "UPDATE tbl_invoice SET status = 'canceled' WHERE invoice_id = :id";
            $update = $pdo->prepare($cancel_query);
            $update->bindParam(':id', $id, PDO::PARAM_INT);

            if ($update->execute()) {
                $pdo->commit();
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Info", "La transaction a été annulée et le stock restauré.", "success", {
                    button: "Continuer",
                        });
                    });
                    </script>';
            } else {
                $pdo->rollback();
            }
        } else {
            // Déjà annulée ou introuvable
            $pdo->rollback();
        }
    } catch (Exception $e) {
        $pdo->rollback();
        echo '<script>alert("Erreur lors de l\'annulation: ' . $e->getMessage() . '");</script>';
    }
}

// Définir les paramètres POST pour l'exportation
$export_params = http_build_query($_REQUEST);

// --- 4. GESTION DU FILTRE DE STATUT (Visualisation) ---
// Par défaut on affiche 'saved', sinon on prend la valeur passée en GET
$view_status = $_REQUEST['view_status'] ?? 'saved';
?>

<html>

<head></head>

</html>

<?php
include("include/stat_op_caisse.php");
?>

<section class="content container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des transactions :
                <?php echo ($view_status == 'saved') ? '<span class="label label-success">VALIDÉES</span>' : '<span class="label label-danger">ANNULÉES</span>'; ?>
            </h3>

            <div class="pull-right">

                <div class="btn-group" style="margin-right: 15px;">
                    <a href="order.php?view_status=saved" class="btn btn-sm <?php echo ($view_status == 'saved') ? 'btn-success disabled' : 'btn-default'; ?>">
                        <i class="fa fa-check"></i> Validées
                    </a>
                    <a href="order.php?view_status=canceled" class="btn btn-sm <?php echo ($view_status == 'canceled') ? 'btn-danger disabled' : 'btn-default'; ?>">
                        <i class="fa fa-times"></i> Annulées
                    </a>
                </div>

                <button onclick="openDesktopExportWindow('print_current_view.php', '<?php echo $export_params; ?>', 'PrintListView')" class="btn btn-warning btn-sm" style="margin-right: 5px;" title="Imprimer la liste filtrée">
                    <i class="fa fa-print"></i> Imprimer
                </button>

                <button type="button" onclick="openDesktopExportWindow('export_pdf.php', '<?php echo $export_params; ?>', 'SalesPDF')" class="btn btn-primary btn-sm" style="margin-right: 5px;" title="PDF">
                    <i class="fa fa-file-pdf-o"></i> PDF
                </button>

                <button type="button" onclick="downloadFile('export_excel.php', '<?php echo $export_params; ?>')" class="btn btn-success btn-sm" style="margin-right: 5px;" title="Excel">
                    <i class="fa fa-file-excel-o"></i> Excel
                </button>
                <?php
                if ($_SESSION["role"] == "Operator") {
                ?>
                    <a href="create_order.php" class="btn btn-info btn-sm">Nouvelle Transaction</a>
                <?php
                }
                ?>
            </div>

            <?php
            // --- LOGIQUE D'ALERTE DE STOCK ---
            if (($_SESSION['count_alert'] ?? 0) > 0 && isset($_POST['save_order'])) {
                // (Votre code existant d'alerte stock - inchangé)
                include("include/notif_PDF_email_stock_alert.php");
            }
            ?>

        </div>
        <div class="box-body">
            <div style="overflow-x:auto;">
                <table class="table table-striped" id="myOrder">
                    <thead>
                        <tr>
                            <th style="width:20px;">No</th>
                            <th style="width:100px;">Opérateur</th>
                            <th style="width:100px;">Client</th>
                            <th style="width:100px;">Date</th>
                            <th style="width:100px;">Montant</th>
                            <th style="width:100px;">Tva</th>
                            <th style="width:100px;">Mode Pay.</th>
                            <th style="width:50px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT * FROM tbl_invoice";
                        $conditions = [];
                        $params = [];

                        // --- 1. FILTRE PAR STATUT (saved vs canceled) ---
                        $conditions[] = "status = :status";
                        $params[':status'] = $view_status;

                        // --- FILTRES DATE ET SHOP ---
                        if (isset($_POST['date_filter'])) {
                            $leshop = $_POST['shop'] ?? 'all';

                            $conditions[] = "order_date BETWEEN :fromdate AND :todate";
                            $params[':fromdate'] = $_POST['date_1'];
                            $params[':todate'] = $_POST['date_2'];

                            if (($_SESSION['role'] ?? '') == "Admin" && $leshop != "all") {
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
                                $params[':leshop'] = $leshop;
                            } elseif (($_SESSION['role'] ?? '') != "Admin") {
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                                $params[':magasin'] = $magasin;
                            }
                        } else {
                            if (($_SESSION['role'] ?? '') != "Admin") {
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                                $params[':magasin'] = $magasin;
                            }
                        }

                        // Construction finale de la requête
                        if (!empty($conditions)) {
                            $sql .= " WHERE " . implode(" AND ", $conditions);
                        }

                        $sql .= " ORDER BY invoice_id DESC";

                        $select = $pdo->prepare($sql);
                        $select->execute($params);

                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                                <td class="text-uppercase">
                                    <?php
                                    $s = $row->id_client;
                                    $sel = $pdo->prepare("SELECT * FROM users WHERE username=:username");
                                    $sel->bindParam(':username', $s);
                                    $sel->execute();
                                    $row1 = $sel->fetch(PDO::FETCH_ASSOC);

                                    echo $row->id_client . "_" . ($row1 ? $row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname'] : 'Client Inconnu');
                                    ?>
                                </td>

                                <td><?php echo $row->order_date; ?></td>
                                <td><?php echo number_format($row->total); ?>&nbsp; FCFA</td>
                                <td><?php echo number_format($row->tva); ?>&nbsp; FCFA</td>
                                <td><?php echo $row->payment_mode; ?>&nbsp;</td>
                                <td>
                                    <?php
                                    // Bouton Supprimer (Annuler) : Seulement si Admin ou Responsable (jour même)
                                    // ET seulement si la vue actuelle est 'saved' (on n'annule pas ce qui est déjà annulé)
                                    if ($view_status == 'saved' && (($_SESSION['role'] ?? '') == "Admin" || (($_SESSION['role'] ?? '') == "Responsable" && $row->order_date == $today_date))) { ?>
                                        <a href="order.php?id=<?php echo $row->invoice_id; ?>&view_status=saved" onclick="return confirm('Êtes-vous sûr de vouloir ANNULER cette transaction ? Le stock sera restauré.')" class="btn btn-danger btn-sm" title="Annuler la transaction"><i class="fa fa-trash"></i></a>
                                    <?php } ?>

                                    <button type="button" onclick="openDesktopReceiptWindow(<?php echo $row->invoice_id; ?>)" class="btn btn-info btn-sm" title="Imprimer le Reçu"><i class="fa fa-print"></i></button>
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
</div>
</section>
</div>
<script>
    $(document).ready(function() {
        $('#myOrder').DataTable();
    });
</script>

<script>
    $('#datepicker_1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        todayBtn: "linked",
    });

    $('#datepicker_2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        todayBtn: "linked"
    });

    function openDesktopExportWindow(page, params, windowName) {
        var url = page + '?' + params;
        var features = 'width=1000,height=700,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';
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
        var url = 'misc/nota.php?id=' + invoiceId;
        var windowName = 'ReceiptPrint' + invoiceId;
        var features = 'width=400,height=600,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';
        var newWindow = window.open(url, windowName, features);
        newWindow.onload = function() {
            newWindow.print();
        };
    }
</script>

<script>
    var ctx = document.getElementById('myChart');
    if (ctx) {
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($date ?? []); ?>,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: <?php echo json_encode($total ?? []); ?>,
                    backgroundColor: 'rgb(13, 192, 58)',
                    borderColor: 'rgb(32, 204, 75)',
                    borderWidth: 1
                }]
            },
            options: {}
        });
    }
</script>

<style>
    .color {
        backgroundColor: rgb(120, 102, 102);
    }
</style>

<script>
    var ctx = document.getElementById('myBestSellItem');
    if (ctx) {
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($pname ?? []); ?>,
                datasets: [{
                    label: 'Total Produit Best Sellers',
                    data: <?php echo json_encode($qty ?? []); ?>,
                    backgroundColor: 'rgb(120,112,175)',
                    borderColor: 'rgb(255,255,255)',
                    borderWidth: 1
                }]
            },
            options: {}
        });
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>