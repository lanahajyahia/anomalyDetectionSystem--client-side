<?php
session_start();
include('includes/header.php');
include('../server.php');
require_once("../exportData.php");

unset($_SESSION["captcha-show"]);

$table = "Detected_Attacks";
function get_total_attacks($type = null)
{
    global $connection, $table;
    $sql = "SELECT * FROM $table WHERE type='$type'";
    $result = $connection->query($sql);
    /* determine number of rows result set */
    if (!empty($result) && $result->num_rows > 0) {
        return $result->num_rows;
    } else {
        return 0;
    }
}
function get_attacks($month1, $month2, $type = null)
{
    global $connection, $table;
    $count_amount = 0;
    $sql = "SELECT date FROM $table WHERE type='$type'";
    $result = $connection->query($sql);
    /* determine number of rows result set */
    if (!empty($result) && $result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $month_str = $row['date'];
            if (substr($month_str, 5, 2) == $month1 || substr($month_str, 5, 2) == $month2) {
                $count_amount++;
            }else if (substr($month_str, 3,2) == $month1 || substr($month_str, 3,2) == $month2) {
                $count_amount++;
            }
        }
    }
    return $count_amount;
}
function get_attack_number_today($type)
{
    global $connection, $table;
    $date = date("d-m-Y") . "";
    $sql = "SELECT * FROM $table WHERE type='$type' and date='$date'";
    $result = $connection->query($sql);
    /* determine number of rows result set */
    if (!empty($result) && $result->num_rows > 0) {
        return $result->num_rows;
    } else {
        return 0;
    }
}
if (isset($_GET['export'])) {
    exportAttack($table);
    exit();
}
?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include("../navbar.php"); ?>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800" style="padding-top: 1em; padding-left: 0.5em;">Dashboard <?php echo date("d/m/Y"); ?></h1>
                <a href="?export" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate report for all attacks</a>
            </div>

            <!-- Content Row -->
            <div class="row" style="justify-content: center;">
                <!-- SQLi -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        SQLi attacks</div>
                                    <div class="h6 mb-0 text-gray-800">
                                        <?php
                                        echo get_attack_number_today(htmlspecialchars("sqli")) . " detected today";
                                        ?>
                                    </div>

                                </div>

                                <div class="col-auto">

                                    <i class="fas fa-exclamation-triangle" style='font-size:36px'></i>

                                </div>
                            </div>
                        </div>
                        <a href="<?php echo htmlspecialchars('Logs\sqliLogs.php'); ?>" class="small-box-footer" style="align-self: center;">View All Logs </i></a>

                    </div>
                </div>

                <!-- XSS Reflected -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Reflected XSS attacks</div>
                                    <div class="h6 mb-0  text-gray-800">
                                        <?php
                                        echo get_attack_number_today(htmlspecialchars("xss reflected")) . " detected today";
                                        ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class='fas fa-skull-crossbones' style='font-size:36px'></i>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo htmlspecialchars('Logs\reflectedLogs.php'); ?>" class="small-box-footer" style="align-self: center;">View All Logs </i></a>

                    </div>
                </div>

                <!-- Stored XSS -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stored xss attacks
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h6 mb-0 mr-3 text-gray-800">
                                                <?php
                                                echo get_attack_number_today(htmlspecialchars("stored")) . " detected today";
                                                ?></div>
                                        </div>
                                        <div class="col">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class='fas fa-radiation-alt' style='font-size:36px'></i>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo htmlspecialchars('Logs\storedLogs.php'); ?>" class="small-box-footer" style="align-self: center;">View All Logs </i></a>

                    </div>
                </div>


            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4" style="align-items:center;">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

                        <!-- <body> -->
                        <canvas id="myChart"></canvas>
                        <p> red - xss reflected , green - sqli , blue - xss stored </p>

                        <script>
                            var xValues = ['Jan & Feb', 'Mar & Apr', 'May & Jun', 'Jul & Aug', 'Sep & Oct', 'Nov & Dec'];

                            new Chart("myChart", {
                                type: "line",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        data: [<?php echo get_attacks("01", "02", "xss reflected"); ?>, <?php echo get_attacks("03", "04", "xss reflected"); ?>, <?php echo get_attacks("05", "06", "xss reflected"); ?>, <?php echo get_attacks("07", "08", "xss reflected"); ?>, <?php echo get_attacks("09", "10", "xss reflected"); ?>, <?php echo get_attacks("11", "12", "xss reflected"); ?>],
                                        borderColor: "red",
                                        fill: false
                                    }, {
                                        data: [<?php echo get_attacks("01", "02", "sqli"); ?>, <?php echo get_attacks("03", "04", "sqli"); ?>, <?php echo get_attacks("05", "06", "sqli"); ?>, <?php echo get_attacks("07", "08", "sqli"); ?>, <?php echo get_attacks("09", "10", "sqli"); ?>, <?php echo get_attacks("11", "12", "sqli"); ?>],
                                        borderColor: "green",
                                        fill: false
                                    }, {
                                        data: [<?php echo get_attacks("01", "02", "xss stored"); ?>, <?php echo get_attacks("03", "04", "xss stored"); ?>, <?php echo get_attacks("05", "06", "xss stored"); ?>, <?php echo get_attacks("07", "08", "xss stored"); ?>, <?php echo get_attacks("09", "10", "xss stored"); ?>, <?php echo get_attacks("11", "12", "xss stored"); ?>],
                                        borderColor: "blue",
                                        fill: false
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


                            <canvas id="myChart1" style="width:100%;max-width:600px;height: 300px;"></canvas>

                            <script>
                                var xValues = ["SQLI", "STORED", "REFLECTED"];
                                var yValues = [<?php echo get_total_attacks("sqli"); ?>, <?php echo get_total_attacks("xss stored"); ?>, <?php echo get_total_attacks("xss reflected"); ?>];
                                var barColors = [
                                    "#00aba9",
                                    "#2b5797",
                                    "#e8c3b9"

                                ];

                                new Chart("myChart1", {
                                    type: "pie",
                                    data: {
                                        labels: xValues,
                                        datasets: [{
                                            backgroundColor: barColors,
                                            data: yValues
                                        }]
                                    },
                                    options: {
                                        title: {
                                            display: true,
                                            text: "Total amount of attacks"
                                        }
                                    }
                                });
                            </script>

                        </div>
                        <!-- Card Body -->

                    </div>
                </div>
            </div>


            <!-- End of Main Content -->

            <?php
            include('includes/scripts.php');
            include('includes/footer.php'); ?>