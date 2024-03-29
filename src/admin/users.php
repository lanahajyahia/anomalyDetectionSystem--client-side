<?php
session_start();
include("../registration-functions.php");
require_once("../exportData.php");


$table = 'Users';
if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $_SESSION['id-to-delete'] = $id;
    include("delete_confirmbox.php");
}
if (isset($_GET['edit-id'])) {
    $id    = (int) $_GET["edit-id"];
    $query = $connection->query("SELECT username, user_type,password FROM `$table` WHERE id='$id'");
    $row = mysqli_fetch_assoc($query);
    $_SESSION['username-to-edit'] = $row['username'] . "";
    $_SESSION['type-to-edit'] = $row['user_type'] . "";
    $_SESSION['id-to-edit'] = $id;
    include('edituser.php');
}

if (isset($_GET['export'])) {
    exportUsers();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Users Table</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/usersstyle.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

</head>

<body id="page-top">
    <?php include("../navbar.php"); ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800" style="padding:10px;">System Users</h1>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Admins only have permission to access user's table</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>

                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Registration date</th>
                                    <th>Last Login</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                $sql   = $connection->query("SELECT id, email, username, reg_date, last_activity, user_type FROM `$table`");
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    if ($row['id'] == 1 || $_SESSION['user']['username'] == $row['username']) {
                                        echo '
										<tr class=odd>
						                  <td>' . $row['username'] . '</td>
                                          <td>' . $row['email'] . '</td>
                                          <td>' . $row['reg_date'] . '</td>	
                                          <td>' . $row['last_activity'] . '</td>
                                          <td>' . $row['user_type'] . '</td>
                                          <td> 
                                          <a href="?edit-id=' . $row['id'] . '" class="btn btn-flat btn-primary"><i class="fas fa-edit"></i>Edit</a> 
                                          </td>
										  	
										
						
										</tr>
';
                                    } else {

                                        echo '
										<tr class=odd>
						                  <td>' . $row['username'] . '</td>
                                          <td>' . $row['email'] . '</td>
                                          <td>' . $row['reg_date'] . '</td>	
                                          <td>' . $row['last_activity'] . '</td>
                                          <td>' . $row['user_type'] . '</td>
                                          <td> 
                                          <a href="?edit-id=' . $row['id'] . '" class="btn btn-flat btn-primary"><i class="fas fa-edit"></i>Edit</a> 
                                          <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-danger" style="display: inline-flex;"><i class="fas fa-trash" aria-hidden="true"></i>Delete</a>
                                          </td>
										  	
										
						
										</tr>
';
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-heading">

                <a href="?export" class="btn btn-success pull-right">Export to excel</a>
            </div>


        </div>

        <div class="col-md-3" style="padding-top: 3.8em;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add User</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <p style="font-size=1em" > <?php echo display_error(); ?> </p>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="fullname" placeholder="Full Name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="username" placeholder="Username" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="email" name="email" placeholder="Email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" placeholder="Password" name="password_1" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" placeholder="Confirm password" name="password_2" class="form-control" required>
                            </div>

                        </div>
                        <p class="usertypep">user type</p>
                        <input class="input-usertype" type="radio" id="admin" name="usertype" value="admin">
                        <label class="label-usertype" for="admin">Admin</label>
                        <input class="input-usertype" type="radio" id="user" name="usertype" value="user">
                        <label class="label-usertype" for="user">User</label><br>
                </div>
                <div class="card-footer">
                    <button class="btn btn-flat btn-primary" name="add_user" type="submit">Add</button>
                    <button type="reset" class="btn btn-flat btn-default">Reset</button>
                </div>
            </div>
            </form>


        </div>

    </div>
    </div>
    <!-- /.container-fluid -->


    </div>
    <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>

</html>
<?php
include('includes/scripts.php');
include('includes/footer.php');
