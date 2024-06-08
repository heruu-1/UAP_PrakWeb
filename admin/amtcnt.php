<?php

require '../helpers/init_conn_db.php';

if (!$conn) {
    die("Connection Failed");
}

$sql = "SELECT SUM(cost) FROM ticket";
$amountsum = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row_amountsum = mysqli_fetch_assoc($amountsum);
$totalRows_amountsum = mysqli_num_rows($amountsum);

$amount_in_rupiah = 'Rp' . number_format($row_amountsum['SUM(cost)'], 0, ',', '.') . ',00';

echo $amount_in_rupiah;
?>
