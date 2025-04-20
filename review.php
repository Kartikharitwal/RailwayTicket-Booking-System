<?php 
    session_start();
    include('DBConnection.php');

    // redirect if not logged in as admin
    if(!isset($_SESSION["admin_uname"])){
        header("location: ./Adminlogin.php?logout=1");
        exit();
    }

    include("adminheader2.html");

    $result = null;
    $result1 = null;

    if (isset($_GET['show'])) {
        $pnr = $_GET['pnr'];

        // Use LEFT JOINs to avoid "invalid pnr" issue due to null joins
        $sql = "SELECT COUNT(p.pno) AS passangers, t.train_no, t.train_name, s.source, s.destination, ti.ticket_no,
                ti.phno, ti.status, s.depart_time, s.arrival_time, ti.date, ti.username
                FROM ticket ti
                LEFT JOIN passanger p ON p.ticket_no = ti.ticket_no
                LEFT JOIN station s ON s.station_no = ti.station_no
                LEFT JOIN train t ON t.train_no = s.train_no
                WHERE ti.ticket_no = '$pnr'
                GROUP BY ti.ticket_no";

        $result = $conn->query($sql);

        $sql1 = "SELECT * FROM passanger WHERE ticket_no = '$pnr'";
        $result1 = $conn->query($sql1);
    }
?>

<!doctype html>
<html lang="en">
<head>
    <title>IR</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="asset/css/animate.css">
    <link rel="stylesheet" href="asset/css/hover-min.css">
    <link rel="stylesheet" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>
    <style>
        .logo {
            border-radius: 1000px;
        }
        div.shadow-cust {
            width: 230px;
            background-color: #DCEEFF;
        }
        .shadow-cust {
            box-shadow: 3px 3px 5px 0px #333;
        }
        i.fa-circle {
            box-shadow: inset 0px 0px 3px 0px #222;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
            <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-8">
            <div class="container">
                <form name="payForm" class="m-5 p-5 border bg-light" method="get">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="navbar-brand text-primary">PNR Status / Cancel Ticket</h4>
                        </div>
                        <div class="col-8">
                            <input class="form-control" type="text" placeholder="Enter PNR Number" name="pnr" id="pnr" maxlength="5" required>
                            <span id="err_pnr" class="text-danger"></span>
                        </div>
                        <div class="col-4">      
                            <input type="submit" class="btn btn-dark text-light" value="Get Status" name="show">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Train Ticket Info -->
            <div class="container">
                <table class="table table-hover bg-light text-center">
                    <?php 
                    if (isset($_GET['show'])) {
                        if ($result && $result->num_rows > 0) {
                            $data = $result->fetch_assoc();
                    ?>
                    <tr class="table-primary">
                        <th>PNR No.</th>
                        <th>Status</th>
                        <th>No. of Passengers</th>
                        <th>Train No.</th>
                        <th>Train Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Date</th>
                        <th>Mobile</th>
                        <th>Booked By</th>
                    </tr>
                    <tr>
                        <td><?php echo $data['ticket_no']; ?></td>
                        <td class="text-danger font-weight-bold"><?php echo $data['status']; ?></td>
                        <td><?php echo $data['passangers']; ?></td>
                        <td><?php echo $data['train_no']; ?></td>
                        <td><?php echo $data['train_name']; ?></td>
                        <td><?php echo $data['source']; ?></td>
                        <td><?php echo $data['destination']; ?></td>
                        <td><?php echo $data['depart_time']; ?></td>
                        <td><?php echo $data['arrival_time']; ?></td>
                        <td><?php echo $data['date']; ?></td>
                        <td><?php echo $data['phno']; ?></td>
                        <td><?php echo $data['username']; ?></td>
                    </tr>
                    <?php 
                        } else {
                            echo "<script>alert('Invalid PNR');</script>";
                        }
                    } 
                    ?>
                </table>
            </div>

            <!-- Passenger Details -->
            <div class="container">
                <table class="table table-hover bg-light text-center">
                    <?php 
                    if (isset($_GET['show'])) {
                        if ($result1 && $result1->num_rows > 0) {
                    ?>
                    <tr class="table-primary">
                        <th>PNO</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Seat No.</th>
                    </tr>
                    <?php
                            while($data1 = $result1->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $data1['pno']; ?></td>
                        <td><?php echo $data1['p_name']; ?></td>
                        <td><?php echo $data1['p_age']; ?></td>
                        <td><?php echo $data1['p_gender']; ?></td>
                        <td><?php echo $data1['seat_no']; ?></td>
                    </tr>
                    <?php 
                            }
                        }
                    } 
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
