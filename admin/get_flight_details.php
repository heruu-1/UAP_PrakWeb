<?php
require '../helpers/init_conn_db.php';

if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];
    $sql = 'SELECT * FROM Flight WHERE flight_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $flight_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $flight = mysqli_fetch_assoc($result);
        echo json_encode($flight);
    } else {
        echo json_encode(['error' => 'SQL error']);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(['error' => 'Invalid flight ID']);
}
?>
