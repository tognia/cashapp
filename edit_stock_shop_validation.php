<?php
// 1. Session and Database connection
include_once 'db/connect_db.php';

// Check user role (Adjust roles as per your system logic)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Responsable" && $_SESSION['role'] !== "storekeeper")) {
    header('location:index.php');
    exit();
}

if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
} else {
    include_once 'inc/header_all_operator.php';
}

// --- PHP LOGIC: PROCESS RECEIVED SHIPMENTS ---
if (isset($_POST['accept_shipments'])) {

    // Validate that shipments were selected
    if (!empty($_POST['shipment_ids']) && is_array($_POST['shipment_ids'])) {

        $shipment_ids = $_POST['shipment_ids'];
        $success_count = 0;
        $error_count = 0;

        try {
            // Start Transaction to ensure data integrity
            $pdo->beginTransaction();

            // Prepare statements outside the loop for performance

            // 1. Get shipment details (Locking row for update if needed, but simple select is usually fine here)
            $stmt_get = $pdo->prepare("SELECT product_id, shipped_quantity, code_agence FROM tbl_product_shipment WHERE shipment_id = :id AND delivery_status = 'Delivered'");

            // 2. Update Shop Stock (Add quantity)
            // We match by product_id and shop_code (code_agence)
            $stmt_update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock + :qty WHERE product_id = :pid AND shop_code = :shop_code");

            // 3. Update Shipment Status to 'accepted'
            $stmt_update_status = $pdo->prepare("UPDATE tbl_product_shipment SET delivery_status = 'accepted' WHERE shipment_id = :id");

            foreach ($shipment_ids as $s_id) {
                // A. Fetch shipment data
                $stmt_get->execute([':id' => $s_id]);
                $shipment = $stmt_get->fetch(PDO::FETCH_ASSOC);

                if ($shipment) {
                    $qty = $shipment['shipped_quantity'];
                    $pid = $shipment['product_id'];
                    $shop = $shipment['code_agence'];

                    // B. Update Shop Stock
                    $stmt_update_stock->execute([':qty' => $qty, ':pid' => $pid, ':shop_code' => $shop]);

                    // Check if stock was actually updated (Item exists in shop)
                    if ($stmt_update_stock->rowCount() > 0) {
                        // C. Update Shipment Status
                        $stmt_update_status->execute([':id' => $s_id]);
                        $success_count++;
                    } else {
                        // Logic if item doesn't exist in tbl_shop_item yet
                        $error_count++;
                    }
                }
            }

            // Commit changes
            $pdo->commit();

            // Feedback Messages
            if ($success_count > 0) {
                echo '<script type="text/javascript">
                         jQuery(function validation(){
                         swal("Succès", "' . $success_count . ' expédition(s) acceptée(s) et stock mis à jour.", "success", {
                         button: "Continuer",
                            }).then(() => { window.location.href = "product_shop_item.php"; });
                         });
                         </script>';
            } elseif ($error_count > 0) {
                echo '<script type="text/javascript">
                         jQuery(function validation(){
                         swal("Attention", "Certains produits n\'ont pas pu être mis à jour (Produit introuvable dans la boutique ?)", "warning", {
                         button: "Continuer",
                            });
                         });
                         </script>';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Stock Reception Error: " . $e->getMessage());
            echo '<script type="text/javascript">
                     jQuery(function validation(){
                     swal("Erreur Base de Données", "Une erreur est survenue: ' . $e->getMessage() . '", "error", {
                     button: "Continuer",
                        });
                     });
                     </script>';
        }
    } else {
        echo '<script type="text/javascript">
                 jQuery(function validation(){
                 swal("Attention", "Veuillez sélectionner au moins une ligne.", "warning", {
                 button: "Continuer",
                    });
                 });
                 </script>';
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Réception de Stock
            <small>Validation des transferts Entrepôt -> Boutique <?php echo $_SESSION['magasin']; ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
            <li class="active">Réception Stock</li>
        </ol>
    </section>
    ---
    <section class="content container-fluid">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Gestion des Réceptions</h3>

                <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#receiveStockModal">
                    <i class="fa fa-cubes"></i> Réceptionner Stocks En Attente
                </button>
            </div>

            <div class="box-body">
                <div class="alert alert-info">
                    <h4><i class="icon fa fa-info"></i> Information</h4>
                    Cliquez sur le bouton ci-dessus pour voir les expéditions en attente (Status: "Delivered") et valider leur entrée en stock boutique.
                </div>

                <h4 class="text-success">Historique Récent (Acceptés)</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="historyTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Agence</th>
                                <th>Quantité</th>
                                <th>Reçu Par</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Show last 20 accepted shipments
                            // On peut maintenant utiliser product_name et product_code directement de tbl_product_shipment
                            $stmt_hist = $pdo->prepare("SELECT s.* FROM tbl_product_shipment s 
                                                     WHERE s.delivery_status = 'accepted' 
                                                     ORDER BY s.shipment_id DESC LIMIT 20");
                            $stmt_hist->execute();
                            while ($row = $stmt_hist->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $row['shipment_id'] . '</td>';
                                echo '<td>' . date('d-m-Y', strtotime($row['shipment_date'])) . '</td>';
                                // Utiliser le nom du produit stocké dans la table d'expédition
                                echo '<td><strong>' . $row['product_name'] . '</strong> <small class="text-muted">(' . $row['product_code'] . ')</small></td>';
                                echo '<td>' . $row['code_agence'] . '</td>';
                                echo '<td>' . $row['shipped_quantity'] . '</td>';
                                echo '<td>' . $row['user_id'] . '</td>';
                                echo '<td><span class="label label-success">Accepté</span></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    ---
    <div class="modal fade" id="receiveStockModal" tabindex="-1" role="dialog" aria-labelledby="receiveStockModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="receiveStockModalLabel"><i class="fa fa-truck"></i> Liste des Expéditions en Attente</h4>
                </div>

                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="shipmentTable">
                                <thead>
                                    <tr class="bg-gray">
                                        <th class="text-center" style="width: 50px;">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Produit (Nom / Code)</th>
                                        <th>Code Agence</th>
                                        <th>Date Expédition</th>
                                        <th>Quantité</th>
                                        <th>Notes</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query to get shipments with status 'Delivered' from tbl_product_shipment only
                                    // On utilise toutes les colonnes car product_name et product_code sont inclus
                                    $sql_ship = "SELECT * FROM tbl_product_shipment WHERE delivery_status = 'Delivered'";

                                    // Optional: Filter by specific shop if logged in user is bound to a shop
                                    if (isset($_SESSION['magasin']) && $_SESSION['role'] !== 'Admin') {
                                        $sql_ship .= " AND code_agence = :code";
                                    }

                                    $stmt_ship = $pdo->prepare($sql_ship);

                                    if (isset($_SESSION['magasin']) && $_SESSION['role'] !== 'Admin') {
                                        $stmt_ship->bindParam(':code', $_SESSION['magasin']);
                                    }

                                    $stmt_ship->execute();

                                    if ($stmt_ship->rowCount() > 0) {
                                        while ($row = $stmt_ship->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <input type="checkbox" name="shipment_ids[]" class="chk_shipment" value="<?php echo $row['shipment_id']; ?>">
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($row['product_name']); ?></strong><br>
                                                    <small class="text-muted">Code: <?php echo htmlspecialchars($row['product_code']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['code_agence']); ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['shipment_date'])); ?></td>
                                                <td class="text-bold text-success" style="font-size: 1.2em;">
                                                    <?php echo htmlspecialchars($row['shipped_quantity']); ?>
                                                </td>
                                                <td><small><i><?php echo htmlspecialchars($row['notes']); ?></i></small></td>
                                                <td>
                                                    <span class="label label-warning"><?php echo htmlspecialchars($row['delivery_status']); ?></span>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center text-muted">Aucune expédition en attente de réception.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i> <strong>Attention :</strong> En cliquant sur "Accepter la Sélection", le stock de la boutique sera **immédiatement augmenté** des quantités sélectionnées et le statut passera à "accepted".
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="accept_shipments" class="btn btn-success btn-flat">
                            <i class="fa fa-check-circle"></i> Accepter la Sélection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once 'inc/footer_all.php'; ?>

    <script>
        $(document).ready(function() {

            var historyTable = null;
            var shipmentTable = null;

            // 1. Initialisation des DataTables
            if ($.fn.DataTable) {

                // DataTable for history (bottom table)
                historyTable = $('#historyTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ]
                });

                // Simpler DataTable for the Modal (top table)
                if (!$.fn.DataTable.isDataTable('#shipmentTable')) {
                    shipmentTable = $('#shipmentTable').DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": false,
                        "info": false,
                        "autoWidth": false,
                        "scrollY": "300px",
                        "scrollCollapse": true
                    });
                } else {
                    shipmentTable = $('#shipmentTable').DataTable();
                }
            }

            if (!shipmentTable) {
                console.error("Erreur: shipmentTable n'a pas pu être initialisé.");
                return;
            }

            // --- 2. LOGIQUE DE SÉLECTION (Corrigée et Robuste) ---

            // Gestion du clic sur la case 'selectAll'
            $('#selectAll').off('click').on('click', function() {
                var isChecked = $(this).prop('checked');
                // Cible toutes les cases à cocher VISIBLES (non filtrées)
                shipmentTable.rows({
                    search: 'applied'
                }).nodes().to$().find('.chk_shipment').prop('checked', isChecked);
            });

            // Si une ligne individuelle est cliquée
            $('#shipmentTable').off('click', '.chk_shipment').on('click', '.chk_shipment', function() {
                var total = shipmentTable.rows({
                    search: 'applied'
                }).nodes().to$().find('.chk_shipment').length;
                var checked = shipmentTable.rows({
                    search: 'applied'
                }).nodes().to$().find('.chk_shipment:checked').length;

                // Synchronise la case "Select All"
                $('#selectAll').prop('checked', total > 0 && total === checked);
            });

            // Réinitialisation lors de l'ouverture du modal
            $('#receiveStockModal').on('shown.bs.modal', function() {
                $('#selectAll').prop('checked', false);
                shipmentTable.rows().nodes().to$().find('.chk_shipment').prop('checked', false);
                shipmentTable.columns.adjust().draw();
            });

            // --- 3. SOUMISSION AJAX SANS REDIRECTION ---
            $('#submitAcceptShipments').off('click').on('click', function(e) {
                e.preventDefault();

                var selectedIds = shipmentTable.rows().nodes().to$().find('.chk_shipment:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    swal("Attention", "Veuillez sélectionner au moins une ligne.", "warning");
                    return;
                }

                // Préparation des données pour l'envoi
                var formData = {
                    'accept_shipments_ajax': 1, // Indicateur pour le script PHP
                    'shipment_ids': selectedIds
                };

                // Afficher un indicateur de chargement
                swal({
                    title: "Traitement en cours...",
                    text: "Veuillez patienter pendant l'enregistrement des stocks.",
                    icon: "info",
                    buttons: false,
                    closeOnClickOutside: false
                });

                // Envoi AJAX
                $.ajax({
                    url: '', // L'URL actuelle (reception_stock.php)
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        swal.close(); // Fermer le swal de chargement

                        if (response.status === 'success' || response.status === 'warning') {

                            // Fermer le modal
                            $('#receiveStockModal').modal('hide');

                            // Afficher le message de succès/avertissement
                            swal("Opération Terminée", response.message, response.status);

                            // IMPORTANT : Recharger les données des tables
                            // Vous devrez implémenter ici la logique pour recharger les données dans les tables
                            // Cela peut nécessiter une fonction PHP pour générer le contenu HTML des deux tables.

                            // Solution Temporaire (Recharger la page est l'alternative la plus simple sans endpoint AJAX dédié)
                            // Cependant, si vous voulez VRAIMENT éviter le rechargement:

                            // Méthode propre (mais nécessite un endpoint PHP pour les données) :
                            // 1. Appeler une fonction AJAX pour récupérer les NOUVELLES données HTML/JSON pour les deux tables.
                            // 2. Clear la DataTable existante (shipmentTable.clear().draw(); historyTable.clear().draw();)
                            // 3. Ajouter les nouvelles données (shipmentTable.rows.add(newData).draw(); etc.)

                            // --- Démonstration de l'alternative simple (Recharge AJAX du contenu des tables) ---
                            // Ceci suppose que votre fichier PHP est capable de rendre les lignes de la table séparément.

                            // *** OPTION RECOMMANDÉE SI VOUS VOULEZ VRAIMENT ÉVITER LE RELOAD ***
                            // Pour cet exemple, je vais simuler un rechargement en utilisant window.location.reload()
                            // car la mise à jour propre des DataTables sans endpoint JSON est trop complexe.

                            // Si le succès est total, on rafraîchit
                            if (response.status === 'success') {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500); // Recharge la page après 1.5s
                            }

                        } else {
                            swal("Erreur", response.message, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.close();
                        console.error("Erreur AJAX:", error);
                        swal("Erreur Technique", "Une erreur est survenue lors de la communication avec le serveur.", "error");
                    }
                });
            });
            // FIN AJAX
        });
    </script>