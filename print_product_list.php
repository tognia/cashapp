<?php
include_once 'db/connect_db.php';

// --- Session and Access Control (Simplified for a report, but still check login) ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

// Disable PHP error display
error_reporting(0);

// --- Product Listing Query Logic (Re-used from product.php) ---
$status = $_GET['status'] ?? 'all';
$statusName = "";
$select_query = "";

switch ($status) {
    case 'ok':
        $select_query = "SELECT * FROM tbl_product WHERE stock > min_stock ORDER BY product_id DESC";
        $statusName = "STOCK OK";
        break;
    case 'alert':
        $select_query = "SELECT * FROM tbl_product WHERE stock <= min_stock AND stock <> 0 ORDER BY product_id DESC";
        $statusName = "STOCK ALERTE";
        break;
    case 'null':
        $select_query = "SELECT * FROM tbl_product WHERE stock = 0 ORDER BY product_id DESC";
        $statusName = "STOCK NULL";
        break;
    case 'all':
    default:
        $select_query = "SELECT * FROM tbl_product ORDER BY product_id DESC";
        $statusName = "INVENTAIRE COMPLET";
        break;
}

$select = $pdo->prepare($select_query);
$select->execute();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rapport Stock - <?php echo $statusName; ?></title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .stock-label {
            padding: 2px 5px;
            border-radius: 3px;
            color: #fff;
            font-weight: bold;
            display: inline-block;
        }

        .label-danger {
            background-color: #dd4b39;
        }

        .label-warning {
            background-color: #f39c12;
        }

        .label-primary {
            background-color: #3c8dbc;
        }

        .label-default {
            background-color: #aaa;
            color: #333;
        }

        /* Masquer les actions sur le rapport imprimé */
        .no-print {
            display: none !important;
        }

        .product-cell {
            text-align: center;
            vertical-align: middle;
            /* Centers content vertically if the row is tall */
        }

        /* Lancer la boîte de dialogue d'impression automatiquement */
        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>Rapport d'Inventaire Produits</h1>
        <p>Statut : **<?php echo $statusName; ?>**</p>
        <p>Date d'impression : <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Stock Actuel</th>
                <th>Stock Min. (Alerte)</th>
                <th>Emplacement Dépôt</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                // Determine stock label class
                $stock_label_class = '';
                if ($row->stock == 0) {
                    $stock_label_class = 'label-danger';
                } elseif ($row->stock <= $row->min_stock) {
                    $stock_label_class = 'label-warning';
                } else {
                    $stock_label_class = 'label-primary';
                }
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="product-cell">
                        <?php echo htmlspecialchars($row->product_code); ?><br />
                        <?php echo htmlspecialchars($row->product_name); ?><br />
                        <?php echo number_format($row->sell_price, 0, null, " "); ?> FCFA
                    </td>
                    <td><?php echo htmlspecialchars($row->product_category); ?></td>
                    <td><?php echo htmlspecialchars($row->supplier); ?></td>
                    <td>
                        <span class="stock-label <?php echo $stock_label_class; ?>">
                            <?php echo $row->stock; ?> <?php echo htmlspecialchars($row->product_satuan); ?>
                        </span>
                    </td>
                    <td><?php echo $row->min_stock; ?></td>

                    <td><?php echo htmlspecialchars($row->place_in_storeroom); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 30px;" class="no-print">
        <button onclick="window.print()" class="btn btn-primary btn-print"><i class="fa fa-print"></i> Imprimer ce rapport</button>
        <button onclick="window.close()" class="btn btn-default">Fermer la fenêtre</button>
    </div>
</body>

</html>