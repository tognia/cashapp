<?php
// Inclure le fichier de connexion et de sécurité
include_once 'db/connect_db.php';

// --- FONCTION POUR LE CONTRÔLE DES CARACTÈRES SPÉCIAUX (é -> e, ç -> c) ---
function clean_special_chars($text)
{
    // Liste des remplacements pour les accents courants
    $unwanted_array = [
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ý' => 'y',
        'ÿ' => 'y',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y'
    ];
    $text = strtr($text, $unwanted_array);

    // Assurez l'encodage HTML après le remplacement
    return htmlspecialchars($text);
}
// --------------------------------------------------------------------------

// Vérification de la session utilisateur
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == "") {
    // Redirection si non connecté
    header('location: index.php');
    exit();
}

// Récupérer l'ID de la facture de l'URL
$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id == 0) {
    // Gérer l'absence d'ID
    echo '<script>swal("Erreur", "ID de facture non spécifié.", "error");</script>';
    exit();
}

// 1. Récupérer les détails de la facture (tbl_invoice)
$req_invoice = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id = :id");
$req_invoice->bindParam(':id', $invoice_id);
$req_invoice->execute();
$invoice_data = $req_invoice->fetch(PDO::FETCH_ASSOC);

if (!$invoice_data) {
    echo '<script>swal("Erreur", "Facture introuvable.", "error");</script>';
    exit();
}

// 2. Récupérer les détails des produits (tbl_invoice_detail)
$req_details = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id = :id");
$req_details->bindParam(':id', $invoice_id);
$req_details->execute();
$product_details = $req_details->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Impression Recu Facture #<?php echo $invoice_id; ?></title>
    <style>
        /* CSS pour le reçu */

        /* AJOUT POUR SUPPRIMER LES EN-TÊTES/PIEDS DE PAGE DU NAVIGATEUR (URL, date, etc.) */
        @page {
            size: auto;
            /* Laisser le navigateur décider ou utiliser auto */
            margin: 0;
            /* Supprime toutes les marges y compris les espaces pour les en-têtes/pieds de page */
        }

        /* Fin de l'ajout */

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            border: 1px dashed #000;
        }

        .receipt h3,
        .receipt h4 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .receipt p {
            padding-top: 5px;
            font-size: 14px;
        }

        .receipt table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        /* Réduire l'espace après le tableau des totaux */
        .receipt .totals {
            margin-bottom: 5px;
        }


        .receipt th,
        .receipt td {
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
            text-align: left;
        }

        /* En-têtes de colonnes */
        .receipt thead th {
            font-size: 14px;
        }

        /* Lignes de détails des produits (TAILLE AUGMENTÉE) */
        .receipt .item-details tbody td {
            font-size: 14px;
            font-weight: normal;
        }

        .receipt .item-details td:nth-child(2) {
            text-align: center;
        }

        /* Qty */
        .receipt .item-details td:nth-child(3) {
            text-align: right;
        }

        /* Price */
        .receipt .item-details td:nth-child(4) {
            text-align: right;
        }

        /* Total */
        .receipt .item-details td:nth-child(4) {
            text-align: right;
        }

        .receipt .totals td {
            border-bottom: none;
            text-align: right;
            font-size: 14px;
        }

        .receipt .totals td:first-child {
            text-align: left;
            font-weight: bold;
        }

        /* TOTAL TTC (Taille 16px en Gras) */
        .receipt .totals tr[style*="font-size: 16px;"] td {
            font-size: 16px !important;
        }

        /* La taille du message de remerciement reste 14px */
        .receipt p[style*="padding-top: 5px"] {
            font-size: 14px;
        }

        /* Masquer les éléments non nécessaires à l'impression */
        @media print {
            body {
                margin: 0;
            }

            .receipt {
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt">
        <img src="./images/logo_nk.png" alt="Logo" style="display: block; margin: 0 auto; max-width: 100px;">
        <h4>Recu de Vente</h4>
        <p style="text-align: center; border-top: 1px dashed #000; padding-top: 5px;">
            Date: <?php echo date("d-m-Y H:i:s", strtotime($invoice_data['order_date'] . ' ' . $invoice_data['time_order'])); ?><br>
            Facture N°: <?php echo $invoice_id; ?><br>
            Opérateur: <?php echo clean_special_chars($invoice_data['cashier_name']); ?>
        </p>

        <table class="item-details">
            <thead>
                <tr>
                    <th style="width: 50%;">Produit</th>
                    <th style="width: 15%; text-align: center;">Qte</th>
                    <th style="width: 20%; text-align: right;">Prix</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product_details as $item): ?>
                    <tr>
                        <td><?php echo clean_special_chars($item['product_name']); ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td><?php echo number_format($item['price'], 0); ?></td>
                        <td><?php echo number_format($item['total'], 0); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Total Remise:</td>
                <td><?php echo number_format($invoice_data['remise'], 0); ?> FCFA</td>
            </tr>
            <tr>
                <td>TVA (19.25%):</td>
                <td><?php echo number_format($invoice_data['tva'], 0); ?> FCFA</td>
            </tr>
            <tr style="font-size: 16px; font-weight: bold;">
                <td>TOTAL TTC:</td>
                <td><?php echo number_format($invoice_data['total'], 0); ?> FCFA</td>
            </tr>
            <tr>
                <td>Mode de Paiement:</td>
                <td><?php echo clean_special_chars($invoice_data['payment_mode']); ?></td>
            </tr>
            <tr>
                <td>Argent Reçu:</td>
                <td><?php echo number_format($invoice_data['paid'], 0); ?> FCFA</td>
            </tr>
            <tr>
                <td>Remboursement:</td>
                <td><?php echo number_format($invoice_data['due'], 0); ?> FCFA</td>
            </tr>
        </table>

        <p style="text-align: center; border-top: 1px dashed #000; padding-top: 5px;">
            Merci de votre achat !
        </p>
    </div>

    <script>
        window.onload = function() {
            // Déclencher la boîte de dialogue d'impression
            window.print();

            // Après l'impression, rediriger vers la page des commandes (ou fermer la fenêtre)
            // Utiliser setTimeout pour s'assurer que l'impression a le temps d'être lancée
            setTimeout(function() {
                window.location.href = 'order.php';
            }, 500);
        }
    </script>

</body>

</html>