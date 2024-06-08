<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['pay_but']) && isset($_SESSION['userId'])) {
    require '../helpers/init_conn_db.php';
    
    $flight_id = $_SESSION['flight_id'];
    $price = $_SESSION['price'];
    $passengers = $_SESSION['passengers'];
    $pass_id = $_SESSION['pass_id'];
    $type = $_SESSION['type'];
    $class = $_SESSION['class'];
    $ret_date = $_SESSION['ret_date'];
    $card_no = $_POST['cc-number'];
    $expiry = $_POST['cc-exp'];

    $sql = 'INSERT INTO PAYMENT (user_id, expire_date, amount, flight_id, card_no) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../payment.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'isiis', $_SESSION['userId'], $expiry, $price, $flight_id, $card_no);
        mysqli_stmt_execute($stmt);

        $stmt = mysqli_stmt_init($conn);
        $flag = false;

        for ($i = $pass_id; $i < $passengers + $pass_id; $i++) { // Correct loop to avoid off-by-one error
            // Check if passenger_id exists in passenger_profile table
            $check_passenger_sql = 'SELECT passenger_id FROM passenger_profile WHERE passenger_id = ?';
            $check_stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($check_stmt, $check_passenger_sql)) {
                header('Location: ../payment.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($check_stmt, 'i', $i);
                mysqli_stmt_execute($check_stmt);
                mysqli_stmt_store_result($check_stmt);

                if (mysqli_stmt_num_rows($check_stmt) == 0) {
                    header('Location: ../payment.php?error=invalidpassenger');
                    exit();
                }

                mysqli_stmt_close($check_stmt);
            }

            // Fetch flight details
            $sql = 'SELECT * FROM Flight WHERE flight_id = ?';
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header('Location: ../payment.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'i', $flight_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    $source = $row['source'];
                    $dest = $row['Destination'];

                    // Seat assignment logic
                    if ($class === 'B') {
                        $last_seat = $row['last_bus_seat'] ?: '0@';
                        $seat_num = (int) substr($last_seat, 0, -1);
                        $seat_alpha = substr($last_seat, -1);
                    } else {
                        $last_seat = $row['last_seat'] ?: '20@';
                        $seat_num = (int) substr($last_seat, 0, -1);
                        $seat_alpha = substr($last_seat, -1);
                    }

                    if ($seat_alpha === 'F') {
                        $seat_num++;
                        $seat_alpha = 'A';
                    } else {
                        $seat_alpha = chr(ord($seat_alpha) + 1);
                    }

                    $new_seat = "{$seat_num}{$seat_alpha}";

                    if ($class === 'B') {
                        $seats = $row['bus_seats'] - 1;
                        $update_sql = "UPDATE Flight SET last_bus_seat = ?, bus_seats = ? WHERE flight_id = ?";
                    } else {
                        $seats = $row['Seats'] - 1;
                        $update_sql = "UPDATE Flight SET last_seat = ?, Seats = ? WHERE flight_id = ?";
                    }

                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $update_sql)) {
                        header('Location: ../payment.php?error=sqlerror');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 'sii', $new_seat, $seats, $flight_id);
                        mysqli_stmt_execute($stmt);
                    }

                    $sql = 'INSERT INTO Ticket (passenger_id, flight_id, seat_no, cost, class, user_id) VALUES (?, ?, ?, ?, ?, ?)';
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header('Location: ../payment.php?error=sqlerror');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 'iisisi', $i, $flight_id, $new_seat, $price, $class, $_SESSION['userId']);
                        mysqli_stmt_execute($stmt);
                        $flag = true;
                    }
                } else {
                    header('Location: ../payment.php?error=sqlerror');
                    exit();
                }
            }
        }

        if ($type === 'round' && $flag === true) {
            $flag = false;
            for ($i = $pass_id; $i < $passengers + $pass_id; $i++) { // Correct loop to avoid off-by-one error
                $sql = 'SELECT * FROM Flight WHERE source = ? AND Destination = ? AND DATE(departure) = ?';
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header('Location: ../payment.php?error=sqlerror');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, 'sss', $dest, $source, $ret_date);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        $flight_id = $row['flight_id'];

                        // Seat assignment logic
                        if ($class === 'B') {
                            $last_seat = $row['last_bus_seat'] ?: '0@';
                            $seat_num = (int) substr($last_seat, 0, -1);
                            $seat_alpha = substr($last_seat, -1);
                        } else {
                            $last_seat = $row['last_seat'] ?: '20@';
                            $seat_num = (int) substr($last_seat, 0, -1);
                            $seat_alpha = substr($last_seat, -1);
                        }

                        if ($seat_alpha === 'F') {
                            $seat_num++;
                            $seat_alpha = 'A';
                        } else {
                            $seat_alpha = chr(ord($seat_alpha) + 1);
                        }

                        $new_seat = "{$seat_num}{$seat_alpha}";

                        if ($class === 'B') {
                            $seats = $row['bus_seats'] - 1;
                            $update_sql = "UPDATE Flight SET last_bus_seat = ?, bus_seats = ? WHERE flight_id = ?";
                        } else {
                            $seats = $row['Seats'] - 1;
                            $update_sql = "UPDATE Flight SET last_seat = ?, Seats = ? WHERE flight_id = ?";
                        }

                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $update_sql)) {
                            header('Location: ../payment.php?error=sqlerror');
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, 'sii', $new_seat, $seats, $flight_id);
                            mysqli_stmt_execute($stmt);
                        }

                        $sql = 'INSERT INTO Ticket (passenger_id, flight_id, seat_no, cost, class, user_id) VALUES (?, ?, ?, ?, ?, ?)';
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            header('Location: ../payment.php?error=sqlerror');
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, 'iisisi', $i, $flight_id, $new_seat, $price, $class, $_SESSION['userId']);
                            mysqli_stmt_execute($stmt);
                            $flag = true;
                        }
                    } else {
                        header('Location: ../payment.php?error=noret');
                        exit();
                    }
                }
            }
        }

        if ($flag) {
            unset($_SESSION['flight_id']);
            unset($_SESSION['passengers']);
            unset($_SESSION['pass_id']);
            unset($_SESSION['price']);
            unset($_SESSION['class']);
            unset($_SESSION['type']);
            unset($_SESSION['ret_date']);
            header('Location: ../pay_success.php');
            exit();
        } else {
            header('Location: ../payment.php?error=sqlerror');
            exit();
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header('Location: ../payment.php');
    exit();
}
