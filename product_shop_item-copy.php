<?php
// product_shop_item.php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper" || $_SESSION['role'] == "Responsable") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
error_reporting(0);

// --- 1. RIGOROUS SHOP SELECTION LOGIC ---
if (isset($_POST['select_shop'])) {
    $_SESSION['select_shop'] = $_POST['shop'];
}

$shop = '';
if ($_SESSION['role'] == "Responsable") {
    $shop = $_SESSION['magasin'] ?? '';
} elseif ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
    $shop = $_SESSION['select_shop'] ?? '';
} else {
    $shop = $_SESSION['magasin'] ?? '';
}

if (!$shop && ($_SESSION['role'] == "Responsable" || $_SESSION['role'] == "Operator")) {
    echo '<script>swal("Attention", "Aucun magasin attribué.", "warning").then(() => { window.location.href = "index.php"; });</script>';
    exit();
}

$id = $_GET['id'] ?? null;

// --- 2. DELETE Logic (Shop Item) ---
if ($id && $shop && ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper")) {
    $delete = $pdo->prepare("DELETE FROM tbl_shop_item WHERE shop_code = :shop AND product_id = :id");
    $delete->bindParam(':shop', $shop);
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        echo '<script>jQuery(function(){ swal("Info", "Produit Supprimé du Magasin", "info").then(() => { window.location.href = "product_shop_item.php"; }); });</script>';
    }
}

// --- 3. MASS STOCK TRANSFER LOGIC ---
if (isset($_POST['mass_transfer_stock'])) {
    $transfers = $_POST['transfers'] ?? [];
    $shipment_date = $_POST['mass_shipment_date'] ?? date('Y-m-d');
    $notes = $_POST['mass_notes'] ?? '';
    $shop_code_target = $_POST['shop_code_target'] ?? '';
    $current_user_id = $_SESSION['user_id'] ?? null;
    $success_count = 0;

    if ($current_user_id && $shop_code_target && !empty($transfers)) {
        try {
            $pdo->beginTransaction();
            $update_main_stock = $pdo->prepare("UPDATE tbl_product SET stock = stock - :quantity WHERE product_code = :product_code");
            $insert_shipment = $pdo->prepare("INSERT INTO tbl_product_shipment (shipment_date, product_id, shipped_quantity, code_agence, user_id, delivery_status, notes, product_code, product_sku, product_name) VALUES (:date, :product_id, :quantity, :agence, :user_id, 'Delivered', :notes, :pcode, :psku, :pname)");

            foreach ($transfers as $transfer) {
                $p_id = $transfer['product_id'];
                $qty = $transfer['quantity'];
                $p_code = $transfer['product_code'];

                $check = $pdo->prepare("SELECT stock, product_sku, product_name FROM tbl_product WHERE product_code = :code");
                $check->execute([':code' => $p_code]);
                $main_p = $check->fetch(PDO::FETCH_ASSOC);

                if ($main_p && $main_p['stock'] >= $qty) {
                    $update_main_stock->execute([':quantity' => $qty, ':product_code' => $p_code]);
                    $insert_shipment->execute([
                        ':date' => $shipment_date,
                        ':product_id' => $p_id,
                        ':quantity' => $qty,
                        ':agence' => $shop_code_target,
                        ':user_id' => $current_user_id,
                        ':notes' => $notes,
                        ':pcode' => $p_code,
                        ':psku' => $main_p['product_sku'],
                        ':pname' => $main_p['product_name']
                    ]);
                    $success_count++;
                }
            }
            $pdo->commit();
            echo '<script>jQuery(function(){ swal("Succès", "' . $success_count . ' transferts effectués.", "success").then(() => { window.location.href="product_shop_item.php"; }); });</script>';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<script>jQuery(function(){ swal("Erreur", "Échec du transfert.", "error"); });</script>';
        }
    }
}

// --- 4. Database Query for Listing ---
$status = $_GET['status'] ?? 'all';
$statusName = "";
$delivered_subquery = "(SELECT COALESCE(SUM(tps.shipped_quantity), 0) FROM tbl_product_shipment tps WHERE tps.product_code = tsi.product_code AND tps.delivery_status = 'Delivered' AND tps.code_agence = :shop_param) AS total_delivered";

$sql = "SELECT tsi.*, $delivered_subquery FROM tbl_shop_item tsi WHERE tsi.shop_code = :shop_where ";
if ($status == 'ok') {
    $sql .= "AND tsi.stock > tsi.min_stock ";
    $statusName = " (STOCK OK)";
}
if ($status == 'alert') {
    $sql .= "AND tsi.stock <= tsi.min_stock AND tsi.stock <> 0 ";
    $statusName = " (ALERTE)";
}
if ($status == 'null') {
    $sql .= "AND tsi.stock = 0 ";
    $statusName = " (STOCK NULL)";
}
$sql .= "ORDER BY tsi.product_name ASC";

$select = $pdo->prepare($sql);
if ($shop) {
    $select->bindParam(':shop_param', $shop);
    $select->bindParam(':shop_where', $shop);
}

// Produits dispo à l'entrepôt
$products_in_main = $pdo->query("SELECT product_code, product_name FROM tbl_product WHERE stock > 0 ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$agencies_list = ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper") ? $pdo->query("SELECT code_agence, libelle_agence FROM agence")->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<div class="content-wrapper">
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Boutique : <b><?php echo htmlspecialchars($shop); ?></b> <?php echo $statusName; ?></h3>

                <div class="pull-right">
                    <a href="product_shop_item.php?status=ok" class="btn btn-primary btn-sm">STOCK OK</a>
                    <a href="product_shop_item.php?status=alert" class="btn btn-warning btn-sm">ALERTE</a>
                    <a href="product_shop_item.php?status=null" class="btn btn-danger btn-sm">NULL</a>
                    <a href="product_shop_item.php?status=all" class="btn btn-default btn-sm">TOUS</a>

                    <button type="button" onclick="openPrintWindow('<?php echo htmlspecialchars($status); ?>')" class="btn btn-info btn-sm">
                        <i class="fa fa-print"></i> Imprimer
                    </button>

                    <?php if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper"): ?>
                        <form action="" method="POST" style="display:inline-block; margin-left:10px;">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <select name="shop" class="form-control" required>
                                    <?php foreach ($agencies_list as $row): ?>
                                        <option value="<?php echo $row['code_agence']; ?>" <?php echo ($row['code_agence'] == $shop) ? 'selected' : ''; ?>>
                                            <?php echo $row['code_agence'] . ' ' . $row['libelle_agence']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" name="select_shop" class="btn btn-default btn-flat">SÉLECTIONNER</button>
                                </span>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="box-header with-border">
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#massTransferModal"><i class="fa fa-truck"></i> Transfert Entrepôt</button>
                    <a href="edit_stock_shop_validation.php" class="btn btn-success btn-sm"><i class="fa fa-cubes"></i> Réceptions en attente</a>
                </div>
            </div>

            <div class="box-body">
                <table class="table table-striped" id="myProduct">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produit</th>
                            <th>Code</th>
                            <th>IMG</th>
                            <th>Prix Vente</th>
                            <th>Stock</th>
                            <th>Transit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $select->execute();
                        while ($row = $select->fetch(PDO::FETCH_OBJ)):
                            $lbl = ($row->stock == 0) ? 'label-danger' : (($row->stock <= $row->min_stock) ? 'label-warning' : 'label-primary');
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row->product_name; ?></td>
                                <td><?php echo $row->product_code; ?></td>
                                <td><img src="upload/<?php echo $row->img; ?>" width="40"></td>
                                <td><?php echo number_format($row->sell_price, 0, null, " "); ?> FCFA</td>
                                <td><span class="label <?php echo $lbl; ?>"><?php echo $row->stock; ?></span></td>
                                <td><span class="label label-danger"><?php echo $row->total_delivered; ?></span></td>
                                <td>
                                    <a href="view_product_shop.php?id=<?php echo $row->product_id; ?>&shop=<?php echo $shop; ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="massTransferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-truck"></i> Transfert vers Boutique : <?php echo $shop; ?></h4>
            </div>
            <form action="product_shop_item.php" method="POST" onsubmit="return confirmMassTransfer();">
                <div class="modal-body">
                    <input type="hidden" name="shop_code_target" value="<?php echo $shop; ?>">

                    <div class="row" style="margin-bottom:15px; background:#f9f9f9; padding:10px; border-radius:5px;">
                        <div class="col-md-6">
                            <label>Date d'Expédition</label>
                            <input type="date" name="mass_shipment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Notes</label>
                            <input type="text" name="mass_notes" class="form-control" placeholder="Note ou Réf sortie...">
                        </div>
                    </div>

                    <div class="row" style="background:#eef6ff; padding:15px; border:1px solid #337ab7; border-radius:5px;">
                        <div class="col-md-6">
                            <label>1. Choisir dans la liste (Tri A-Z)</label>
                            <select id="product_select_dropdown_transfer" class="form-control select2" style="width:100%;">
                                <option value="">-- Chercher un produit --</option>
                                <?php foreach ($products_in_main as $prod): ?>
                                    <option value="<?php echo $prod['product_code']; ?>">
                                        <?php echo htmlspecialchars($prod['product_name'] . ' [' . $prod['product_code'] . ']'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>2. Scanner / Saisie Manuelle</label>
                            <div class="input-group">
                                <input type="text" id="product_code_input_transfer" class="form-control" placeholder="Code produit...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="add_transfer_line_btn"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary">
                                <th>Produit</th>
                                <th width="100">Entrepôt</th>
                                <th width="100">Boutique</th>
                                <th width="130">Qté Transfert</th>
                                <th width="50">Action</th>
                            </tr>
                        </thead>
                        <tbody id="transfer_lines_container">
                            <tr id="no-transfer-row">
                                <td colspan="5" class="text-center text-muted">Aucun produit ajouté.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="mass_transfer_stock" id="submit_mass_transfer" class="btn btn-primary" disabled>Confirmer les Transferts</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // --- IMPRESSION ---
    function openPrintWindow(status) {
        var url = 'print_shop_item_list.php?status=' + status;
        var features = 'width=900,height=700,scrollbars=yes,resizable=yes';
        window.open(url, 'PrintShopList', features);
    }

    $(document).ready(function() {
        if ($.fn.DataTable) $('#myProduct').DataTable({
            "order": [
                [0, "asc"]
            ]
        });

        // Select2 corrigé
        if ($.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('#massTransferModal'),
                placeholder: "-- Chercher un produit --",
                allowClear: true
            });
        }

        let line_idx = 0;

        function addLine(code) {
            if (!code || code === "") return;
            if ($(`.pcode-check[value="${code}"]`).length > 0) {
                swal("Attention", "Produit déjà dans la liste", "warning");
                return;
            }

            $.getJSON('fetch_product_main_and_shop.php', {
                code: code,
                shop: '<?php echo $shop; ?>'
            }, function(res) {
                if (res.status === 'success') {
                    line_idx++;
                    let row = `<tr>
                    <td><b>${res.data.product_name}</b><br><small>${res.data.product_code}</small>
                        <input type="hidden" name="transfers[${line_idx}][product_id]" value="${res.data.product_id}">
                        <input type="hidden" name="transfers[${line_idx}][product_code]" value="${res.data.product_code}">
                        <input type="hidden" class="pcode-check" value="${res.data.product_code}">
                    </td>
                    <td class="text-center"><span class="label label-success">${res.data.main_stock}</span></td>
                    <td class="text-center"><span class="label label-info">${res.data.shop_stock}</span></td>
                    <td><input type="number" name="transfers[${line_idx}][quantity]" class="form-control" value="1" min="1" max="${res.data.main_stock}"></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-xs remove-line"><i class="fa fa-times"></i></button></td>
                </tr>`;
                    $('#transfer_lines_container').append(row);
                    $('#no-transfer-row').hide();
                    $('#submit_mass_transfer').prop('disabled', false);
                    $('#product_code_input_transfer').val('').focus();
                    $('#product_select_dropdown_transfer').val(null).trigger('change');
                } else {
                    swal("Erreur", res.message, "error");
                }
            });
        }

        // Ajout par dropdown
        $('#product_select_dropdown_transfer').on('select2:select', function(e) {
            addLine(e.params.data.id);
        });

        // Ajout par bouton +
        $('#add_transfer_line_btn').on('click', function() {
            let code = $('#product_code_input_transfer').val().trim();
            if (code !== "") {
                addLine(code);
            } else {
                let dropVal = $('#product_select_dropdown_transfer').val();
                if (dropVal) addLine(dropVal);
                else swal("Info", "Veuillez scanner ou choisir un produit.", "info");
            }
        });

        $('#product_code_input_transfer').keypress(e => {
            if (e.which == 13) {
                e.preventDefault();
                addLine($(e.target).val().trim());
            }
        });

        $(document).on('click', '.remove-line', function() {
            $(this).closest('tr').remove();
            if ($('#transfer_lines_container tr').length <= 1) {
                $('#no-transfer-row').show();
                $('#submit_mass_transfer').prop('disabled', true);
            }
        });
    });

    function confirmMassTransfer() {
        return confirm("Confirmer l'expédition ?");
    }
</script>

<?php include_once 'inc/footer_all.php'; ?>