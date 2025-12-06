<?php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if (empty($_SESSION['user_name'])) {
    // Ne pas rediriger, juste s'assurer que les variables de session existent ou les initialiser
}

// Disable PHP error display
error_reporting(0);

// Récupération des paramètres
$status = $_GET['status'] ?? 'all';
$shop = $_GET['shop'] ?? ($_SESSION['magasin'] ?? ''); // Utiliser le shop de l'URL ou celui de la session

if (!$shop) {
    echo "Erreur : Le code magasin est manquant.";
    exit();
}

// --- Product Listing Query Logic (Adapté de product_shop_item.php) ---
$statusName = "";

// SQL Subquery to calculate the total delivered quantity for the current shop ($shop)
$delivered_quantity_subquery = "
    (
        SELECT COALESCE(SUM(tps.shipped_quantity), 0)
        FROM tbl_product_shipment tps
        WHERE tps.product_code = tsi.product_code
        AND tps.delivery_status = 'Delivered'
        AND tps.code_agence = :shop_param
    ) AS total_delivered
";

$base_select_columns = "tsi.*, $delivered_quantity_subquery";
$base_from_table = "tbl_shop_item tsi";
$base_where_clause = "tsi.shop_code = :shop_where";

switch ($status) {
    case 'all':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause ORDER BY tsi.product_id DESC");
        $statusName = "TOUS LES PRODUITS";
        break;
    case 'ok':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock > tsi.min_stock AND $base_where_clause ORDER BY tsi.product_id DESC");
        $statusName = "STOCK OK";
        break;
    case 'alert':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock <= tsi.min_stock AND tsi.stock <> 0 AND $base_where_clause ORDER BY tsi.product_id DESC");
        $statusName = "STOCK ALERTE";
        break;
    case 'null':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock = 0 AND $base_where_clause ORDER BY tsi.product_id DESC");
        $statusName = "STOCK NULL";
        break;
    default:
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause ORDER BY tsi.product_id DESC");
        $statusName = "TOUS LES PRODUITS";
        break;
}

// BINDING DES PARAMÈTRES
if (isset($select)) {
    $select->bindParam(':shop_param', $shop);
    $select->bindParam(':shop_where', $shop);
    $select->execute();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rapport Stock Magasin - <?php echo $statusName; ?> (<?php echo htmlspecialchars($shop); ?>)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .stock-status-ok {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }

        .stock-status-alert {
            background-color: #fff3cd;
            color: #856404;
            font-weight: bold;
        }

        .stock-status-null {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>Rapport d'Inventaire Magasin : <?php echo htmlspecialchars($shop); ?></h1>
        <p>Statut des Produits : **<?php echo $statusName; ?>**</p>
        <p>Date d'impression : <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Libellé Produit</th>
                <th>Catégorie</th>
                <th>Prix Vente</th>
                <th>Stock Magasin</th>
                <th>Stock Min.</th>
                <th>Expédié Non Validé</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (isset($select) && $select->rowCount() > 0) {
                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                    // Déterminer la classe de style pour le stock
                    $stock_class = '';
                    if ($row->stock == 0) {
                        $stock_class = 'stock-status-null';
                    } elseif ($row->stock <= $row->min_stock) {
                        $stock_class = 'stock-status-alert';
                    } else {
                        $stock_class = 'stock-status-ok';
                    }
            ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row->product_code); ?></td>
                        <td><?php echo htmlspecialchars($row->product_name); ?></td>
                        <td><?php echo htmlspecialchars($row->product_category); ?></td>
                        <td><?php echo number_format($row->sell_price, 0, null, " "); ?> FCFA</td>
                        <td class="<?php echo $stock_class; ?>">
                            <?php echo $row->stock; ?> <?php echo htmlspecialchars($row->product_satuan); ?>
                        </td>
                        <td><?php echo $row->min_stock; ?></td>
                        <td>
                            <?php
                            $delivered_qty = $row->total_delivered ?? 0;
                            echo $delivered_qty > 0 ? $delivered_qty : '0';
                            ?>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="8" style="text-align: center;">Aucun produit trouvé avec ce statut pour ce magasin.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="no-print">
        <button onclick="window.print()">Imprimer ce rapport</button>
        <button onclick="window.close()">Fermer la fenêtre</button>
    </div>
</body>

</html>