<?php
session_start();
require("../../server.php");

require_once("../../exportData.php");

$table = 'Detected_Attacks';
$type =  'sqli';
if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];
    $query = $connection->query("DELETE FROM `$table` WHERE id='$id'");
}
if (isset($_GET['delete-all'])) {
    $query = $connection->query("DELETE FROM `$table` WHERE type='$type'");
}
if (isset($_GET['export'])) {
    exportAttack($table, $type);
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

    <title><?php echo htmlspecialchars('SQL Injections Logs'); ?></title>

    <!-- Custom fonts for this template -->
    <!-- <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        ul {
            background-color: transparent !important;
        }

        .btn.btn-flat {
            border-radius: 19px;
        }
    </style>
</head>

<body id="page-top">
    <?php include("../../navbar.php"); ?>

    <!-- Page Wrapper -->
    <div id="wrapper">
        </nav>
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800" style="padding:10px;">SQL Injections Logs <i class="fas fa-exclamation-triangle" style='font-size:36px'></i></h1>
           
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SQL injection is a server-side vulnerability that targets the application's database.</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><i class="far fa-calendar-alt"></i> Date</th>
                                    <th><i class="far fa-clock"></i> Time</th>
                                    <th><i class="fas fa-desktop"></i> HTTP url</th>
                                    <th><i class="fas fa-cog"></i> HTTP method</th>
                                    <th><i class="fas fa-map"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql   = $connection->query("SELECT id, date, time, hostname, http_method, description FROM `$table` WHERE type='$type'");
                                if ($sql->num_rows == 0) {
                                    // echo "empty";
                                    $_SESSION['empty-table-sqli'] = 'empty';
                                } else {
                                    $_SESSION['empty-table-sqli'] = 'not';
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        echo '
										<tr>
						                  <td>' . $row['date'] . '</td>
                                          <td>' . $row['time'] . '</td>
						                  <td>' . $row['hostname'] . '</td>
										  <td>' . $row['http_method'] . '</td>
										  <td>
										  <a href="details.php?id=' . $row['id'] . '" target="_blank" class="btn btn-flat btn-primary"><i class="fas fa-tasks"></i> Details</a>
                                          
										  <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-danger"><i class="fas fa-times"></i> Delete</a>
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
                <a href="?delete-all" class="btn btn-success pull-right btn-danger" title="Delete all logs"><i class="fas fa-trash"></i> Delete All</a>

                <?php if ($_SESSION['empty-table-sqli'] == 'empty') : ?>
                    <a href="" class="btn btn-success pull-right" style="pointer-events: none;">Export to excel</a>
                <?php else : ?>
                    <a href="?export" class="btn btn-success pull-right">Export to excel</a>
                <?php endif; ?>
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
include('../includes/scripts.php');
include('../includes/footer.php'); ?>