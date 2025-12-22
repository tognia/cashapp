<?php
// pending_orders.php
include_once 'db/connect_db.php';

// Vérification de la session
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == "") {
    header('location:index.php');
    exit();
}

if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
} else {
    include_once 'inc/header_all_operator.php';
}

// ---------------------------------------------------------
// LOGIQUE DE SUPPRESSION (DELETE LOGIC)
// ---------------------------------------------------------
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // 1. Sécurité : Vérifier que c'est bien une commande "pending" avant de supprimer
    // On ne veut pas qu'un utilisateur supprime une vente réelle "saved" par accident via l'URL
    $check_stmt = $pdo->prepare("SELECT status FROM tbl_invoice WHERE invoice_id = :id");
    $check_stmt->execute([':id' => $id]);
    $row = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['status'] == 'pending') {
        try {
            $pdo->beginTransaction();

            // Supprimer les détails (tbl_invoice_detail)
            $delete_details = $pdo->prepare("DELETE FROM tbl_invoice_detail WHERE invoice_id = :id");
            $delete_details->bindParam(':id', $id);
            $delete_details->execute();

            // Supprimer l'entête (tbl_invoice)
            $delete_inv = $pdo->prepare("DELETE FROM tbl_invoice WHERE invoice_id = :id");
            $delete_inv->bindParam(':id', $id);
            $delete_inv->execute();

            $pdo->commit();

            echo '<script>
                jQuery(function(){
                    swal("Supprimé", "Le brouillon a été supprimé avec succès.", "success").then(() => {
                        window.location.href = "pending_orders.php";
                    });
                });
            </script>';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<script>
                jQuery(function(){
                    swal("Erreur", "Impossible de supprimer : ' . $e->getMessage() . '", "error");
                });
            </script>';
        }
    } else {
        echo '<script>
            jQuery(function(){
                swal("Erreur", "Action non autorisée ou commande introuvable.", "error");
            });
        </script>';
    }
}
// ---------------------------------------------------------
// FIN LOGIQUE SUPPRESSION
// ---------------------------------------------------------
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>📋 Commandes en Attente (Suspendues)</h1>
    </section>

    <section class="content">
        <div class="box box-warning">
            <div class="box-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sélectionner uniquement les status 'pending'
                        $select = $pdo->prepare("SELECT i.*, 
                            CASE 
                                WHEN i.id_client = 'common' THEN 'Client Régulier'
                                ELSE CONCAT(u.firstname, ' ', u.lastname)
                            END as client_name
                            FROM tbl_invoice i
                            LEFT JOIN users u ON i.id_client = u.username
                            WHERE i.status = 'pending' 
                            ORDER BY i.invoice_id DESC");
                        $select->execute();

                        while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>
                                <td>#' . $row['invoice_id'] . '</td>
                                <td>' . date('d-m-Y H:i', strtotime($row['order_date'] . ' ' . $row['time_order'])) . '</td>
                                <td>' . $row['client_name'] . '</td>
                                <td>' . number_format($row['total'], 0) . ' FCFA</td>
                                <td>
                                    <a href="create_order.php?edit_id=' . $row['invoice_id'] . '" class="btn btn-info btn-sm" title="Reprendre la vente">
                                        <i class="fa fa-share"></i> Reprendre
                                    </a>

                                    <a href="pending_orders.php?delete_id=' . $row['invoice_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Voulez-vous vraiment supprimer définitivement ce brouillon ?\')" title="Supprimer">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($select->rowCount() == 0): ?>
                    <div class="alert alert-info text-center" style="margin-top:20px;">
                        <i class="fa fa-info-circle"></i> Aucune commande en attente pour le moment.
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <a href="create_order.php" class="btn btn-primary">Retour à la caisse</a>
            </div>
        </div>
    </section>
</div>

<?php include_once 'inc/footer_all.php'; ?>