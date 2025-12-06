<?php
// print_current_view.php

// Démarrer la session si nécessaire (pour récupérer le rôle et le magasin)

// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Désactiver l'affichage des erreurs (bonne pratique en production)
error_reporting(0);

// --- LOGIQUE DE RÉCUPÉRATION DES DONNÉES ET FILTRES (Identique à order.php) ---

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];
$magasin = $_SESSION['magasin'] ?? '';
// Récupérer les paramètres passés via l'URL (GET)
$leshop = $_GET['shop'] ?? null;
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;

// Déterminer les informations de filtre à afficher
$filter_summary = "Liste des transactions ";
if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;
    $filter_summary .= "du **$fromdate** au **$todate**";
}

if (($_SESSION['role'] ?? '') == "Admin") {
    if ($leshop && $leshop != "all") {
        $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
        $filter_summary .= " pour le magasin **$leshop**";
    } else {
        $filter_summary .= " pour **Tous les magasins**";
    }
} elseif (($_SESSION['role'] ?? '') != "Admin") {
    // Opérateur/Responsable ne voit que son magasin
    $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
    $params[':magasin'] = $magasin;
    $filter_summary .= " pour le magasin **$magasin**";
}

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
    <title>Impression - Transactions Filtrées</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20mm;
            /* Marge pour l'impression */
        }

        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5px;
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

        /* Masquer la barre d'action du navigateur lors de l'impression */
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
        <p class="filter-info">
            <?php echo str_replace('**', '', $filter_summary); ?> | Généré le : <?php echo date('d/m/Y H:i:s'); ?>
        </p>
    </header>

    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">
            Confirmer l'Impression
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px;">
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
            if (!empty($transactions)):
                foreach ($transactions as $row):
                    // Récupération du nom complet du client si possible (comme dans order.php)
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
    </table>

    <div class="footer">
        --- Document généré automatiquement pour impression ---
    </div>

    <script>
        // Ceci est important car le script parent (order.php) ouvre la fenêtre
        // sans barre d'adresse, et nous voulons déclencher l'impression ici.
        // Un délai est souvent nécessaire pour s'assurer que tout le contenu est rendu.
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Délai de 500ms
        };
    </script>

</body>

</html>