<?php
// print_current_view.php


// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Désactiver l'affichage des erreurs
error_reporting(0);

// --- LOGIQUE DE FILTRES ---

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];

$magasin = $_SESSION['magasin'] ?? '';
$role = $_SESSION['role'] ?? '';

// Récupérer les paramètres GET
$leshop = $_GET['shop'] ?? null;
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;
$view_status = $_GET['view_status'] ?? 'saved';

// Titre du rapport selon le statut
$status_label = ($view_status == 'canceled') ? 'ANNULÉES' : 'VALIDÉES';
$filter_summary = "Liste des transactions **" . $status_label . "**";

// 1. Filtre par Statut
$conditions[] = "status = :status";
$params[':status'] = $view_status;

// 2. Filtre par Date
if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;
    $filter_summary .= " du **$fromdate** au **$todate**";
}

// 3. Filtre par Magasin (Sur la colonne 'user')
if ($role == "Admin") {
    if ($leshop && $leshop != "all") {
        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
        $filter_summary .= " pour le magasin **$leshop**";
    } else {
        $filter_summary .= " pour **Tous les magasins**";
    }
} else {
    // Opérateur/Responsable ne voit que son magasin
    $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
    $params[':magasin'] = $magasin;
    $filter_summary .= " pour le magasin **$magasin**";
}

// Construction finale SQL
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY invoice_id DESC";

try {
    $select = $pdo->prepare($sql);
    $select->execute($params);
    $transactions = $select->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erreur de requête SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Impression - Transactions <?php echo $status_label; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20mm;
        }

        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5px;
        }

        .status-header {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
            color: <?php echo ($view_status == 'canceled') ? '#d9534f' : '#000'; ?>;
            text-transform: uppercase;
        }

        .filter-info {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        /* Style spécifique pour les totaux */
        tfoot th {
            background-color: #e0e0e0;
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>Rapport des Transactions</h1>
        <div class="status-header">STATUT : <?php echo $status_label; ?></div>
        <p class="filter-info">
            <?php echo str_replace('**', '', $filter_summary); ?> | Généré le : <?php echo date('d/m/Y H:i:s'); ?>
        </p>
    </header>

    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Confirmer l'Impression
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px; cursor: pointer;">
            Fermer la fenêtre
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:15%;">Opérateur</th>
                <th style="width:20%;">Client (ID)</th>
                <th style="width:15%;">Date</th>
                <th style="width:15%;">Montant TTC</th>
                <th style="width:15%;">TVA</th>
                <th style="width:15%;">Mode Paiement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grand_total = 0; // Initialisation total TTC
            $grand_tva = 0;   // Initialisation total TVA

            if (!empty($transactions)):
                foreach ($transactions as $row):
                    // Calcul des sommes
                    $grand_total += $row->total;
                    $grand_tva += $row->tva;

                    // Récupération du nom client
                    $client_display = $row->id_client;
                    $sel = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username=:username");
                    $sel->bindParam(':username', $row->id_client);
                    $sel->execute();
                    $row1 = $sel->fetch(PDO::FETCH_ASSOC);

                    if ($row1 && ($row1['firstname'] || $row1['lastname'])) {
                        $client_display .= " (" . trim($row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname']) . ")";
                    } else {
                        $client_display .= " (Client Inconnu)";
                    }
            ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-uppercase"><?php echo htmlspecialchars($row->cashier_name); ?></td>
                        <td class="text-uppercase"><?php echo htmlspecialchars($client_display); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($row->order_date); ?></td>
                        <td class="text-right"><?php echo number_format($row->total, 0, ',', ' '); ?> FCFA</td>
                        <td class="text-right"><?php echo number_format($row->tva, 0, ',', ' '); ?> FCFA</td>
                        <td class="text-uppercase"><?php echo htmlspecialchars($row->payment_mode); ?></td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="7" class="text-center">Aucune transaction trouvée pour les filtres sélectionnés.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <?php if (!empty($transactions)): ?>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">TOTAUX GÉNÉRAUX</th>
                    <th class="text-right"><?php echo number_format($grand_total, 0, ',', ' '); ?> FCFA</th>
                    <th class="text-right"><?php echo number_format($grand_tva, 0, ',', ' '); ?> FCFA</th>
                    <th></th>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>

    <div class="footer">
        --- Document généré automatiquement pour impression ---
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

</body>

</html>