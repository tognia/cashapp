<?php
// pending_orders.php
include_once 'db/connect_db.php';

// Check session to avoid errors if accessed directly
if (!isset($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
} else {
    include_once 'inc/header_all_operator.php';
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>📋 Mes Commandes en Attente (Suspendues)</h1>
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
                        // We store the current user in a variable
                        $current_user = $_SESSION['user_name'];

                        // Modified Query: Added "AND i.user = :current_user"
                        $select = $pdo->prepare("SELECT i.*, 
                            CASE 
                                WHEN i.id_client = 'common' THEN 'Client Régulier'
                                ELSE CONCAT(u.firstname, ' ', u.lastname)
                            END as client_name
                            FROM tbl_invoice i
                            LEFT JOIN users u ON i.id_client = u.username
                            WHERE i.status = 'pending' AND i.user = :current_user
                            ORDER BY i.invoice_id DESC");

                        // Execute with the user parameter
                        $select->execute([':current_user' => $current_user]);

                        while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>
                                <td>#' . $row['invoice_id'] . '</td>
                                <td>' . date('d-m-Y H:i', strtotime($row['order_date'] . ' ' . $row['time_order'])) . '</td>
                                <td>' . $row['client_name'] . '</td>
                                <td>' . number_format($row['total'], 0) . ' FCFA</td>
                                <td>
                                    <a href="create_order.php?edit_id=' . $row['invoice_id'] . '" class="btn btn-info btn-sm">
                                        <i class="fa fa-share"></i> Reprendre la commande
                                    </a>
                                    <a href="delete_pending.php?id=' . $row['invoice_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Supprimer définitivement ce brouillon ?\')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($select->rowCount() == 0): ?>
                    <div class="alert alert-info text-center">Aucune commande en attente pour votre compte.</div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <a href="create_order.php" class="btn btn-primary">Retour à la caisse</a>
            </div>
        </div>
    </section>
</div>

<?php include_once 'inc/footer_all.php'; ?>