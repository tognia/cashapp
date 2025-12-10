<?php
// Inclure la connexion à la base de données
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
$magasin = $_SESSION['magasin'] ?? ''; // Magasin de l'utilisateur

// --- LOGIQUE DE SUPPRESSION DE TRANSACTION ---
if ($id) {
    // Requête préparée pour supprimer l'invoice et les détails associés
    $delete_query = "DELETE tbl_invoice , tbl_invoice_detail FROM tbl_invoice 
                     INNER JOIN tbl_invoice_detail ON tbl_invoice.invoice_id = tbl_invoice_detail.invoice_id 
                     WHERE tbl_invoice.invoice_id=:id";

    $delete = $pdo->prepare($delete_query);
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        // Affichage d'une alerte de succès via SweetAlert (swal)
        echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "La transaction est supprimée", "info", {
            button: "Continuer",
                });
            });
            </script>';
    }
}

// Définir les paramètres POST pour l'exportation
// Ces paramètres contiennent potentiellement date_1, date_2 et shop
$export_params = http_build_query($_POST);
?>

<html>

<head>
</head>

</html>


<?php
include("include/stat_op_caisse.php");
?>

<section class="content container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des transactions</h3>

            <div class="pull-right">

                <button onclick="openDesktopExportWindow('print_current_view.php', '<?php echo $export_params; ?>', 'PrintListView')" class="btn btn-warning btn-sm" style="margin-right: 5px;" title="Imprimer la liste filtrée">
                    <i class="fa fa-print"></i> Imprimer la liste
                </button>

                <button type="button" onclick="openDesktopExportWindow('export_pdf.php', '<?php echo $export_params; ?>', 'SalesPDF')" class="btn btn-primary btn-sm" style="margin-right: 5px;" title="Ouvrir le PDF dans une fenêtre sans barre de navigation">
                    <i class="fa fa-file-pdf-o"></i> Générer PDF
                </button>

                <button type="button" onclick="downloadFile('export_excel.php', '<?php echo $export_params; ?>')" class="btn btn-success btn-sm" style="margin-right: 5px;" title="Exporter les données en fichier Excel">
                    <i class="fa fa-file-excel-o"></i> Exporter en Excel
                </button>

                <a href="create_order.php" class="btn btn-info btn-sm">Nouvelle Transaction</a>
            </div>


            <?php
            // --- LOGIQUE D'ALERTE DE STOCK ---
            if (($_SESSION['count_alert'] ?? 0) > 0 && isset($_POST['save_order'])) {
                echo "PRODUITS STOCK ALERT : " . $_SESSION['count_alert'];

                for ($j = 0; $j < $_SESSION['count_alert']; $j++) {
                    echo $j . "__";
                    echo $_SESSION['tab_alert']['id'][$j] ?? '';
                    echo $_SESSION['tab_alert']['code'][$j] ?? '';
                    echo $_SESSION['tab_alert']['name'][$j] ?? '';
                    echo $_SESSION['tab_alert']['stock'][$j] ?? '';
                    echo $_SESSION['tab_alert']['stock_min'][$j] ?? '';
                    echo "__" . ($_SESSION['em'] ?? '');
                }

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

                        // --- CONSTRUCTION DE LA REQUÊTE SELON LES FILTRES ET LE RÔLE ---

                        if (isset($_POST['date_filter'])) {
                            $leshop = $_POST['shop'] ?? 'all';

                            $conditions[] = "order_date BETWEEN :fromdate AND :todate";
                            $params[':fromdate'] = $_POST['date_1'];
                            $params[':todate'] = $_POST['date_2'];

                            if (($_SESSION['role'] ?? '') == "Admin" && $leshop != "all") {
                                // Admin filtrant par magasin
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
                                $params[':leshop'] = $leshop;
                            } elseif (($_SESSION['role'] ?? '') != "Admin") {
                                // Opérateur/Responsable ne voit que son magasin
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                                $params[':magasin'] = $magasin;
                            }
                        } else {
                            // Pas de filtre de date, utiliser la logique par défaut
                            if (($_SESSION['role'] ?? '') != "Admin") {
                                // Opérateur/Responsable ne voit que son magasin
                                $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                                $params[':magasin'] = $magasin;
                            }
                            $sql .= " ORDER BY invoice_id DESC";
                        }

                        if (!empty($conditions)) {
                            $sql = "SELECT * FROM tbl_invoice WHERE " . implode(" AND ", $conditions) . " ORDER BY invoice_id DESC";
                        }

                        $select = $pdo->prepare($sql);
                        $select->execute($params);

                        // Affichage du magasin filtré si le filtre est appliqué
                        if (isset($_POST['date_filter'])) { ?>
                            <div><?php echo $leshop; ?></div>
                        <?php }

                        // --- AFFICHAGE DES LIGNES DU TABLEAU ---
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
                                    // Condition de suppression : Admin ou Responsable (uniquement pour les transactions du jour)
                                    if (($_SESSION['role'] ?? '') == "Admin" || (($_SESSION['role'] ?? '') == "Responsable" && $row->order_date == $today_date)) { ?>
                                        <a href="order.php?id=<?php echo $row->invoice_id; ?>" onclick="return confirm('Supprimer la transaction?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
<script>
    // Initialisation de DataTables pour la liste des transactions
    $(document).ready(function() {
        $('#myOrder').DataTable();
    });
</script>


<script>
    /*
     * AMÉLIORATION DU DATEPICKER
     */

    // Datepicker DE DÉBUT
    $('#datepicker_1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        todayBtn: "linked",
    });

    // Datepicker DE FIN
    $('#datepicker_2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        todayBtn: "linked"
    });

    // Assurez-vous que le DataTable pour 'mySalesReport' existe si vous l'initialisez
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#mySalesReport')) {
            $('#mySalesReport').DataTable();
        }
    });


    // --- NOUVELLES FONCTIONS JAVASCRIPT POUR LES FENÊTRES DE STYLE APPLICATION ---

    // Fonction principale pour ouvrir une fenêtre sans barre de navigation (pour PDF/Impression)
    function openDesktopExportWindow(page, params, windowName) {
        var url = page + '?' + params;
        // On rend la fenêtre un peu plus petite que la précédente, plus adaptée à une liste de transactions
        var features = 'width=1000,height=700,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';

        var newWindow = window.open(url, windowName, features);

        // Si la page est 'print_current_view.php', on veut déclencher l'impression après le chargement.
        if (page === 'print_current_view.php') {
            newWindow.onload = function() {
                // S'assurer que la fonction d'impression n'est appelée qu'une seule fois
                newWindow.print();
            };
        }
    }

    // Fonction pour télécharger un fichier (Excel)
    function downloadFile(page, params) {
        var url = page + '?' + params;

        var link = document.createElement('a');
        link.href = url;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Fonction pour imprimer un reçu (misc/nota.php)
    function openDesktopReceiptWindow(invoiceId) {
        var url = 'misc/nota.php?id=' + invoiceId;
        var windowName = 'ReceiptPrint' + invoiceId;
        // Petites dimensions pour un reçu de caisse
        var features = 'width=400,height=600,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';

        var newWindow = window.open(url, windowName, features);

        // Optionnel: Déclencher l'impression immédiatement si c'est un reçu de caisse
        newWindow.onload = function() {
            newWindow.print();
        };
    }
</script>

<script>
    var ctx = document.getElementById('myChart');
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
</script>

<style>
    .color {
        backgroundColor: rgb(120, 102, 102);
    }
</style>


<script>
    var ctx = document.getElementById('myBestSellItem');
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
</script>

<?php
include_once 'inc/footer_all.php';
?>