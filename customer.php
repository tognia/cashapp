<?php
include_once 'db/connect_db.php';

// --- Role-Based Header Include and Session Check ---
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

    // IMPROVEMENT: Use Prepared Statements for DELETE (Crucial Security Fix)
    $delete = $pdo->prepare("DELETE FROM users WHERE user_id = :id");
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Client supprimé", "info", {
                button: "Continue",
                    });
                });
                </script>';
    } else {
        // Log the actual error instead of displaying a generic one with the ID
        // Note: In a production environment, you should log this error, not show it to the user.
        $error_info = $delete->errorInfo();
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Error", "Could not delete client. Error: ' . $error_info[2] . '", "error", {
                button: "Continue",
                    });
                });
                </script>';
    }
}

// --- Insertion Logic ---
if (isset($_POST['submit'])) {
    // Sanitize/Validate input (omitted here for brevity, but recommended)
    $firstname  = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname   = $_POST['lastname'];
    $address    = $_POST['address'];
    $email      = $_POST['email'];
    $contact    = $_POST['contact'];
    $username   = $_POST['username'];
    $password   = sha1($_POST['password']); // Using sha1 as per original code, but recommend modern hashing (password_hash)
    $type       = $_POST['type'];

    // Check if the username already exists
    if (isset($_POST['username'])) {
        // IMPROVEMENT: Use Prepared Statements for SELECT (Crucial Security Fix)
        $select = $pdo->prepare("SELECT username FROM users WHERE username = :username");
        $select->bindParam(':username', $username);
        $select->execute();

        if ($select->rowCount() > 0) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Client déjà enregistré sous ce nom d\'utilisateur", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
            // INSERT Query using Prepared Statements
            $insert = $pdo->prepare("INSERT INTO users(firstname,middlename,lastname,address,email,contact,username,password,type) 
                                     VALUES(:firstname,:middlename,:lastname,:address,:email,:contact,:username,:password,:type)");

            // Binding the values
            $insert->bindParam(':firstname', $firstname);
            $insert->bindParam(':middlename', $middlename);
            $insert->bindParam(':lastname', $lastname);
            $insert->bindParam(':address', $address);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':contact', $contact);
            $insert->bindParam(':username', $username);
            $insert->bindParam(':password', $password);
            $insert->bindParam(':type', $type);

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
                Client Management
                <small>List of Registered Clients</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Clients</li>
            </ol>
        </section>

        <section class="content container-fluid">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">LISTE DES CLIENTS</h3>

                    <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewClientModal">
                        <i class="fa fa-user-plus"></i> Register New Client
                    </button>

                    <?php
                    // Display total count directly from PHP after the last operation
                    $select_count = $pdo->prepare("SELECT COUNT(*) as total FROM users");
                    $select_count->execute();
                    $rowcount = $select_count->fetch(PDO::FETCH_ASSOC)['total'];
                    ?>
                    <div class="pull-right" style="margin-right: 20px; font-weight: bold;">
                        Total Clients : <font color="green" style="font-size: 18px;"><?php echo $rowcount; ?></font>
                    </div>
                </div>
                <div class="box-body" style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="myClientTable">
                        <thead>
                            <tr class="bg-primary">
                                <th>ID Client</th>
                                <th>Client Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>User Name (Login)</th>
                                <th>Client Type</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = $pdo->prepare("SELECT * FROM users ORDER BY user_id DESC");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr class="record">
                                    <td><?php echo $row->user_id; ?></td>
                                    <td><?php echo $row->firstname . " " . $row->middlename . " " . $row->lastname; ?></td>
                                    <td><?php echo $row->address; ?></td>
                                    <td><?php echo $row->email; ?></td>
                                    <td><?php echo $row->contact; ?></td>
                                    <td><?php echo $row->username; ?></td>
                                    <td><?php echo $row->type; ?></td>
                                    <td>
                                        <?php if ($_SESSION['role'] == "Admin") { ?>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="<?php echo $row->user_id; ?>" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <a href="edit_customer.php?id=<?php echo $row->user_id; ?>"
                                                class="btn btn-info btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                                        <?php } ?>
                                        <a href="view_customer.php?id=<?php echo $row->user_id; ?>"
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
    <div class="modal fade" id="addNewClientModal" tabindex="-1" role="dialog" aria-labelledby="addNewClientModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addNewClientModalLabel"><i class="fa fa-user-plus"></i> Enregistrer un nouveau Client</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="firstname">Prenom</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter Prenom" required>
                        </div>
                        <div class="form-group">
                            <label for="middlename">Second Nom</label>
                            <input type="text" class="form-control" name="middlename" placeholder="Enter Second Nom" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Nom de Famille / Society</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse Client</label>
                            <input type="text" class="form-control" name="address" placeholder="Enter Full Address" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact Client</label>
                            <input type="text" class="form-control" name="contact" placeholder="Enter Full Contact" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Login</label>
                            <input type="text" class="form-control" name="username" placeholder="Username/Login" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de Passe</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label>TYPE CLIENT</label>
                            <select class="form-control" name="type" required>
                                <option value="particulier">Particulier</option>
                                <option value="entreprise">Entreprise</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize DataTables (using myClientTable for clarity)
            if ($.fn.DataTable) {
                $('#myClientTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ] // Sort by the first column (ID) descending
                });
            }

            // Use swal for deletion confirmation 
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var clientId = $(this).data('id');
                var deleteUrl = 'customer.php?id=' + clientId; // Assuming this file is named customer.php

                swal({
                        title: "Are you sure?",
                        text: "You are about to delete this client record. This action cannot be undone!",
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