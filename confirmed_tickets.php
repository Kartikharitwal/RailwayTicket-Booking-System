<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    $isLoggedIn = false;
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, 'admin') !== false) {
            $isLoggedIn = true;
            break;
        }
    }
    if (!$isLoggedIn) {
        header("Location: Adminlogin.php");
        exit();
    }
}

// Include database connection
include("DBConnection.php"); // Assumes $conn is defined here

// Initialize variables
$trainNumber = "";
$results = [];
$message = "";
$hasSearched = false;

// Process form submission
if (isset($_POST['search'])) {
    $trainNumber = mysqli_real_escape_string($conn, $_POST['train_number']);
    $hasSearched = true;

    if (empty($trainNumber)) {
        $message = "Please enter a train number";
    } else {
        $trainCheckQuery = "SELECT * FROM train WHERE train_no = '$trainNumber'";
        $trainCheckResult = mysqli_query($conn, $trainCheckQuery);

        if (!$trainCheckResult) {
            $message = "Error checking train: " . mysqli_error($conn);
        } else if (mysqli_num_rows($trainCheckResult) == 0) {
            $message = "Train number $trainNumber does not exist";
        } else {
            $query = "SELECT t.*, 
                             CONCAT(u.first_name, ' ', u.last_name) AS passenger_name,
                             TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS age,
                             u.gender,
                             tr.train_name,
                             st.source,
                             st.destination
                      FROM ticket t
                      INNER JOIN user u ON t.username = u.username
                      INNER JOIN train tr ON t.train_no = tr.train_no
                      INNER JOIN station st ON t.station_no = st.station_no AND st.train_no = t.train_no
                      WHERE t.train_no = '$trainNumber' AND t.status = 'booked'
                      ORDER BY t.date DESC";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                $message = "Error executing query: " . mysqli_error($conn);
            } else if (mysqli_num_rows($result) == 0) {
                $message = "No booked tickets found for train number $trainNumber";
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $results[] = $row;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmed Tickets by Train Number</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <style>
        body { background-color: #f0f0f0; }
        .container { background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 20px; margin-bottom: 20px; }
        .header { background-color: #007bff; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .table th { background-color: #007bff; color: white; }
        .search-box { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .btn-print { float: right; margin-bottom: 10px; }
        @media print {
            .no-print { display: none; }
            .container { width: 100%; max-width: 100%; box-shadow: none; }
            .header, .table th { background-color: #007bff !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <?php include('adminheader1.html'); ?>
        <?php include('adminmenu.html'); ?>
    </div>

    <div class="container">
        <div class="header">
            <h2><i class="fas fa-ticket-alt"></i> Confirmed Tickets by Train Number</h2>
        </div>

        <div class="search-box no-print">
            <form method="POST" action="">
                <div class="form-row align-items-center">
                    <div class="col-sm-4">
                        <label for="train_number">Enter Train Number:</label>
                        <input type="text" class="form-control" id="train_number" name="train_number" value="<?php echo htmlspecialchars($trainNumber); ?>" required>
                    </div>
                    <div class="col-auto" style="margin-top: 30px;">
                        <button type="submit" name="search" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($hasSearched): ?>
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'No') === 0 || strpos($message, 'Error') === 0 || strpos($message, 'Please') === 0 || strpos($message, 'Train number') === 0 ? 'alert-warning' : 'alert-info'; ?> no-print">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if (count($results) > 0): ?>
                <button class="btn btn-success btn-print no-print" onclick="window.print();">
                    <i class="fas fa-print"></i> Print
                </button>

                <div class="train-info mb-4">
                    <h4>Train Details</h4>
                    <p><strong>Train Number:</strong> <?php echo htmlspecialchars($results[0]['train_no']); ?></p>
                    <p><strong>Train Name:</strong> <?php echo htmlspecialchars($results[0]['train_name']); ?></p>
                    <p><strong>Route:</strong> <?php echo htmlspecialchars($results[0]['source']); ?> to <?php echo htmlspecialchars($results[0]['destination']); ?></p>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>PNR</th>
                                <th>Passenger Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Class</th>
                                <th>Seat No.</th>
                                <th>Booking Date</th>
                                <th>Journey Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $ticket): ?>
                                <tr>
                                    <td><?php echo isset($ticket['ticket_no']) ? htmlspecialchars($ticket['ticket_no']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['passenger_name']) ? htmlspecialchars($ticket['passenger_name']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['age']) ? htmlspecialchars($ticket['age']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['gender']) ? htmlspecialchars($ticket['gender']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['class']) ? htmlspecialchars($ticket['class']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['seat_no']) ? htmlspecialchars($ticket['seat_no']) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['date']) ? htmlspecialchars(date('d-m-Y', strtotime($ticket['date']))) : 'N/A'; ?></td>
                                    <td><?php echo isset($ticket['date']) ? htmlspecialchars(date('d-m-Y', strtotime($ticket['date']))) : 'N/A'; ?></td>
                                    <td><span class="badge badge-success">Booked</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="summary mt-4">
                    <p><strong>Total Confirmed Tickets:</strong> <?php echo count($results); ?></p>
                    <p><strong>Report Generated:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.navbar-nav a');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'confirmed_tickets.php') {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
