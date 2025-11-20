<?php
include_once 'db/connect_db.php';

// --- Role-Based Header Include ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    // Determine which header to include based on role
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

// error_reporting(0); // Better to fix errors than hide them, but keeping it commented out for now.

// --- Deletion Logic ---
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // IMPROVEMENT: Use Prepared Statements for DELETE
    $delete = $pdo->prepare("DELETE FROM supliers WHERE suplier_id = :id");
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Fournisseur supprimé", "info", {
                button: "Continue",
                    });
                });
                </script>';
    }
}

// --- Insertion Logic ---
if (isset($_POST['submit'])) {

    $suplier_name    = $_POST['suplier_name'];
    $suplier_address = $_POST['suplier_address'];
    $suplier_contact = $_POST['suplier_contact'];
    $contact_person  = $_POST['contact_person'];
    $note            = $_POST['note'];

    // Check if the supplier name already exists
    if (isset($_POST['suplier_name'])) {
        // IMPROVEMENT: Use Prepared Statements for SELECT
        $select = $pdo->prepare("SELECT suplier_name FROM supliers WHERE suplier_name = :suplier_name");
        $select->bindParam(':suplier_name', $suplier_name);
        $select->execute();

        if ($select->rowCount() > 0) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Fournisseur déjà enregistré sous ce nom", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
            // INSERT Query using Prepared Statements
            $insert = $pdo->prepare("INSERT INTO supliers(suplier_name,suplier_address,suplier_contact,contact_person,note) 
                                     VALUES(:suplier_name,:suplier_address,:suplier_contact,:contact_person,:note)");

            // Binding the values
            $insert->bindParam(':suplier_name', $suplier_name);
            $insert->bindParam(':suplier_address', $suplier_address);
            $insert->bindParam(':suplier_contact', $suplier_contact);
            $insert->bindParam(':contact_person', $contact_person);
            $insert->bindParam(':note', $note);

            if ($insert->execute()) {
                echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Success", "Enregistrement réussi", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
            }
        }
    }
}
?>

<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: 'src/loading.gif',
            closeImage: 'src/closelabel.png'
        })
    })
</script>

<?php
function createRandomPassword()
{
    // ... (rest of the function remains the same)
    $chars = "003232303232023232023456789";
    srand((float)microtime() * 1000000);
    $i = 0;
    $pass = '';
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
$finalcode = 'RS-' . createRandomPassword();
?>

<script language="javascript" type="text/javascript">
    // ... (rest of the clock functions remains the same)
    var timerID = null;
    var timerRunning = false;

    function stopclock() {
        if (timerRunning)
            clearTimeout(timerID);
        timerRunning = false;
    }

    function showtime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds()
        var timeValue = "" + ((hours > 12) ? hours - 12 : hours)
        if (timeValue == "0") timeValue = 12;
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        document.clock.face.value = timeValue;
        timerID = setTimeout("showtime()", 1000);
        timerRunning = true;
    }

    function startclock() {
        stopclock();
        showtime();
    }
    window.onload = startclock;
</SCRIPT>

<body>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Supplier Management
                <small>List of Registered Suppliers</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Suppliers</li>
            </ol>
        </section>

        <section class="content container-fluid">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Suppliers</h3>

                    <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewSupplierModal">
                        <i class="fa fa-truck"></i> Register New Supplier
                    </button>

                    <?php
                    // Display total count directly from PHP after the last operation
                    $select_count = $pdo->prepare("SELECT COUNT(*) as total FROM supliers");
                    $select_count->execute();
                    $rowcount = $select_count->fetch(PDO::FETCH_ASSOC)['total'];
                    ?>
                    <div class="pull-right" style="margin-right: 20px; font-weight: bold;">
                        Total Suppliers : <font color="green" style="font-size: 18px;"><?php echo $rowcount; ?></font>
                    </div>
                </div>
                <div class="box-body" style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="resultTable">
                        <thead>
                            <tr class="bg-primary">
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Address</th>
                                <th>Contact No.</th>
                                <th>Note</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = $pdo->prepare("SELECT * FROM supliers ORDER BY suplier_id DESC");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr class="record">
                                    <td><?php echo $row->suplier_name; ?></td>
                                    <td><?php echo $row->contact_person; ?></td>
                                    <td><?php echo $row->suplier_address; ?></td>
                                    <td><?php echo $row->suplier_contact; ?></td>
                                    <td><?php echo $row->note; ?></td>
                                    <td>
                                        <?php if ($_SESSION['role'] == "Admin") { ?>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="<?php echo $row->suplier_id; ?>" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <a href="edit_supplier.php?id=<?php echo $row->suplier_id; ?>"
                                                class="btn btn-info btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                                        <?php } ?>
                                        <a href="view_supplier.php?id=<?php echo $row->suplier_id; ?>"
                                            class="btn btn-default btn-sm" title="View Details"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="addNewSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addNewSupplierModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addNewSupplierModalLabel"><i class="fa fa-truck"></i> Register New Supplier</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="suplier_name">Supplier Name</label>
                            <input type="text" class="form-control" name="suplier_name" placeholder="Enter Supplier Name" required>
                        </div>
                        <div class="form-group">
                            <label for="suplier_address">Supplier Address</label>
                            <input type="text" class="form-control" name="suplier_address" placeholder="Enter Full Address" required>
                        </div>
                        <div class="form-group">
                            <label for="suplier_contact">Supplier Contact</label>
                            <input type="text" class="form-control" name="suplier_contact" placeholder="Enter Full Contact" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_person">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person" placeholder="Contact Person Name" required>
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <input type="text" class="form-control" name="note" placeholder="Note (e.g., product specialization)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="submit">Register Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Check if DataTables function is available before calling it (assuming it's loaded in header_all.php)
            if ($.fn.DataTable) {
                $('#resultTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ] // Sort by the first column (Supplier Name) descending
                });
            }


            // Use swal for deletion confirmation 
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var supplierId = $(this).data('id');
                var deleteUrl = 'supplier.php?id=' + supplierId;

                swal({
                        title: "Are you sure?",
                        text: "You are about to delete this supplier record. This action cannot be undone!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location.href = deleteUrl;
                        }
                    });
            });

            // The old AJAX deletion code is replaced by the cleaner swal + redirect approach.
        });
    </script>
</body>
<?php include('inc/footer_all.php'); ?>

</html>