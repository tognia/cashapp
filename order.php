<?php
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Vérification de la session utilisateur
if ($_SESSION['user_name'] == "") {
    header('location:index.php');
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
                <button onclick="window.print()" class="btn btn-warning btn-sm" style="margin-right: 5px;"><i class="fa fa-print"></i> Imprimer la page</button>
                <a href="export_pdf.php?<?php echo http_build_query($_POST); ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Générer PDF</a>
                <a href="export_excel.php?<?php echo http_build_query($_POST); ?>" class="btn btn-success btn-sm" style="margin-right: 5px;"><i class="fa fa-file-excel-o"></i> Exporter en Excel</a>
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
                                $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
                                $params[':leshop'] = $leshop;
                            } elseif (($_SESSION['role'] ?? '') != "Admin") {
                                // Opérateur/Responsable ne voit que son magasin
                                $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
                                $params[':magasin'] = $magasin;
                            }
                        } else {
                            // Pas de filtre de date, utiliser la logique par défaut
                            if (($_SESSION['role'] ?? '') != "Admin") {
                                // Opérateur/Responsable ne voit que son magasin
                                $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
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
                                    <a href="misc/nota.php?id=<?php echo $row->invoice_id; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
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
     * La navigation est assurée nativement par la librairie, mais on ajoute le format
     * de date nécessaire pour la BDD (yyyy-mm-dd) et on s'assure que le calendrier est prêt.
     */

    // Datepicker DE DÉBUT
    $('#datepicker_1').datepicker({
        autoclose: true,
        // Format pour correspondre à la requête SQL (obligatoire pour le filtre)
        format: 'yyyy-mm-dd',
        // Option qui permet de mettre en surbrillance la date du jour
        todayHighlight: true,
        // Ajoute un bouton pour sélectionner rapidement la date d'aujourd'hui
        todayBtn: "linked",
        // Permet de cliquer sur l'en-tête pour passer à la sélection des mois et années.
        // Cette option peut varier ou être absente selon la librairie Datepicker utilisée.
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
        $('#mySalesReport').DataTable();
    });
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