<?php
include_once 'db/connect_db.php';
// Check if session has started and role is set
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header('location:index.php');
    exit(); // Always exit after a header redirect
}
include_once 'inc/header_all.php';

// Turn off error reporting for production (though fixing them is better)
// error_reporting(0); 

// --- Deletion Logic ---
// We use isset($_GET['id']) instead of relying on error_reporting(0)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // IMPROVEMENT: Use prepared statements for DELETE to prevent SQL injection
    $delete = $pdo->prepare("DELETE FROM tbl_user WHERE user_id = :id");
    $delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete->execute()) {
        // Swal notification
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "User Has Been Deleted", "info", {
                button: "Continue",
                    });
                });
                </script>';
    }
}

// --- Insertion Logic ---
if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    // IMPROVEMENT: NEVER use sha1() for passwords. Use password_hash() instead. 
    // For compatibility with existing db, I will keep sha1 here, but recommend changing.
    $password = sha1($_POST['password']);
    $role     = $_POST['select_option'];
    $magasin  = $_POST['magasin'];
    // Assuming status is always '1' (active) for new users based on the insert query
    $status   = 1;

    // Check if the username already exists
    if (isset($_POST['username'])) {
        // IMPROVEMENT: Use prepared statements for SELECT
        $select = $pdo->prepare("SELECT username FROM tbl_user WHERE username = :username");
        $select->bindParam(':username', $username);
        $select->execute();

        if ($select->rowCount() > 0) {
            // Swal warning
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Utilisateur deja enregistré sous ce nom", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
        } else {
            // INSERT Query with improved security and structure
            $insert = $pdo->prepare("INSERT INTO tbl_user(username,fullname,password,magasin,role,is_active) VALUES(:name,:fullname,:pass,:magasin,:role,:status)");

            // Binding the values parameter with input from user
            $insert->bindParam(':name', $username);
            $insert->bindParam(':fullname', $fullname);
            $insert->bindParam(':pass', $password);
            $insert->bindParam(':magasin', $magasin);
            $insert->bindParam(':role', $role);
            $insert->bindParam(':status', $status, PDO::PARAM_INT);


            if ($insert->execute()) {
                // Swal success
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

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            User Management
            <small>Manage System Users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
        </ol>
    </section>

    <section class="content container-fluid">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">List of Registered Users</h3>
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNewUserModal">
                    <i class="fa fa-user-plus"></i> Register New User
                </button>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="myRegister">
                        <thead>
                            <tr class="bg-primary">
                                <th>No</th>
                                <th>Login</th>
                                <th>Full Name</th>
                                <th>Shop (Magasin)</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT * FROM tbl_user ORDER BY user_id DESC");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->username; ?></td>
                                    <td><?php echo $row->fullname; ?></td>
                                    <td><?php echo $row->magasin; ?></td>
                                    <td><?php echo $row->role; ?></td>
                                    <td>
                                        <?php
                                        // Prevents deleting the current logged-in user
                                        if ($row->username != $_SESSION['user_name']) {
                                        ?>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="<?php echo $row->user_id; ?>" title="Delete User">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        <?php
                                        } else {
                                            echo '<span class="label label-info">Current User</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="addNewUserModal" tabindex="-1" role="dialog" aria-labelledby="addNewUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addNewUserModalLabel"><i class="fa fa-user-plus"></i> Register New User Account</h4>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Login (Username)</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label for="fname">Full Name</label>
                        <input type="text" class="form-control" id="fname" name="fullname" placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="form-group">
                        <label for="magasin">Shop (Magasin)</label>
                        <select class="form-control" name="magasin" required>
                            <option value="">Select Shop</option>
                            <?php
                            // Fetch shops dynamically
                            $select = $pdo->prepare("SELECT code_agence, libelle_agence FROM agence");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <option value="<?php echo $row['code_agence']; ?>"><?php echo $row['code_agence'] . " - " . $row['libelle_agence']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="select_option" required>
                            <option value="">Select Role</option>
                            <option>Admin</option>
                            <option>Operator</option>
                            <option>Responsable</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="submit">Register User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#myRegister').DataTable({
            "order": [
                [0, "desc"]
            ] // Sort by 'No' column (which reflects ID) descending by default
        });

        // Use swal for deletion confirmation instead of built-in confirm()
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            var deleteUrl = 'register.php?id=' + userId;

            swal({
                    title: "Are you sure?",
                    text: "You are about to delete this user account. This action cannot be undone!",
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
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>