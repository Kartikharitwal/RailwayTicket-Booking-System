<<<<<<< HEAD
<?php
include('DBConnection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Seat Availability</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<?php
if (isset($_GET['train_id']) && !empty($_GET['train_id'])) {
    $train_id = mysqli_real_escape_string($conn, $_GET['train_id']);

    $query = "SELECT * FROM train WHERE train_no = '$train_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $train = mysqli_fetch_assoc($result);

        $bookings_query = "SELECT COUNT(*) as booked_seats FROM ticket WHERE train_no = '$train_id' AND status = 'booked'";
        $bookings_result = mysqli_query($conn, $bookings_query);
        $bookings = mysqli_fetch_assoc($bookings_result);

        $total_seats = $train['seat_avail'];
        $booked = $bookings['booked_seats'] ?? 0;
        $available_seats = $total_seats - $booked;
?>
        <div class="card p-4 shadow">
            <h2 class="mb-3">Seat Availability for <strong><?php echo $train['train_name']; ?></strong> (Train No: <?php echo $train_id; ?>)</h2>
            <p><strong>Total Seats:</strong> <?php echo $total_seats; ?></p>
            <p><strong>Booked Seats:</strong> <?php echo $booked; ?></p>
            <p><strong>Available Seats:</strong> <?php echo max($available_seats, 0); ?></p>
        </div>
<?php
    } else {
        echo "<div class='alert alert-danger'>Train not found with ID: $train_id</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No train ID provided.</div>";
}
?>

</body>
</html>
=======
<?php
include('DBConnection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Seat Availability</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<?php
if (isset($_GET['train_id']) && !empty($_GET['train_id'])) {
    $train_id = mysqli_real_escape_string($conn, $_GET['train_id']);

    $query = "SELECT * FROM train WHERE train_no = '$train_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $train = mysqli_fetch_assoc($result);

        $bookings_query = "SELECT COUNT(*) as booked_seats FROM ticket WHERE train_no = '$train_id' AND status = 'booked'";
        $bookings_result = mysqli_query($conn, $bookings_query);
        $bookings = mysqli_fetch_assoc($bookings_result);

        $total_seats = $train['seat_avail'];
        $booked = $bookings['booked_seats'] ?? 0;
        $available_seats = $total_seats - $booked;
?>
        <div class="card p-4 shadow">
            <h2 class="mb-3">Seat Availability for <strong><?php echo $train['train_name']; ?></strong> (Train No: <?php echo $train_id; ?>)</h2>
            <p><strong>Total Seats:</strong> <?php echo $total_seats; ?></p>
            <p><strong>Booked Seats:</strong> <?php echo $booked; ?></p>
            <p><strong>Available Seats:</strong> <?php echo max($available_seats, 0); ?></p>
        </div>
<?php
    } else {
        echo "<div class='alert alert-danger'>Train not found with ID: $train_id</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No train ID provided.</div>";
}
?>

</body>
</html>
>>>>>>> 9cde57e9d4fe1346c087eb8f14242abaee368fb0
