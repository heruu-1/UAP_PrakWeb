<?php include_once 'header.php'; ?>
<?php include_once 'footer.php'; ?>
<?php require '../helpers/init_conn_db.php'; ?>

<?php
// Handle Delete Operation
if (isset($_POST['del_ticket']) && isset($_SESSION['adminId'])) {
    $ticket_id = $_POST['ticket_id'];
    $sql = 'DELETE FROM ticket WHERE ticket_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $ticket_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'booked.php';</script>");
        exit();
    }
}

// Handle Update Operation
if (isset($_POST['update_ticket']) && isset($_SESSION['adminId'])) {
    $ticket_id = $_POST['ticket_id'];
    $passenger_id = $_POST['passenger_id'];
    $flight_id = $_POST['flight_id'];
    $user_id = $_POST['user_id'];
    $seat_no = $_POST['seat_no'];
    $cost = $_POST['cost'];
    $class = $_POST['class'];
    $sql = 'UPDATE ticket SET passenger_id=?, flight_id=?, user_id=?, seat_no=?, cost=?, class=? WHERE ticket_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'iiisisi', $passenger_id, $flight_id, $user_id, $seat_no, $cost, $class, $ticket_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'booked.php';</script>");
        exit();
    }
}
?>

<style>
table {
    background-color: white;
}

h1 {
    margin-top: 20px;
    margin-bottom: 20px;
    font-family: 'product sans';
    font-size: 45px !important;
    font-weight: lighter;
}

a:hover {
    text-decoration: none;
}

body {
    background-color: #efefef;
}

th {
    font-size: 22px;
}

td {
    margin-top: 10px !important;
    font-size: 16px;
    font-weight: bold;
    font-family: 'Assistant', sans-serif !important;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    border-radius: 10px;
}

@media screen and (max-width: 768px) {
    .modal-content {
        width: 80%;
    }
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>

<main>
    <div class="container-md mt-2">
        <h1 class="display-4 text-center text-secondary">Booked Tickets</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Passenger</th>
                    <th scope="col">Flight</th>
                    <th scope="col">User</th>
                    <th scope="col">Seat</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Class</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                $sql = 'SELECT * FROM ticket ORDER BY ticket_id ASC';
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <tr class='text-center'>
                        <td scope='row'>".$cnt."</td>
                        <td>".$row['passenger_id']."</td>
                        <td>".$row['flight_id']."</td>
                        <td>".$row['user_id']."</td>
                        <td>".$row['seat_no']."</td>
                        <td>".$row['cost']."</td>
                        <td>".$row['class']."</td>
                        <td>
                            <form action='booked.php' method='post' style='display:inline-block;'>
                                <input name='ticket_id' type='hidden' value='".$row['ticket_id']."'>
                                <button class='btn' type='submit' name='del_ticket'>
                                    <i class='text-danger fa fa-trash'></i>
                                </button>
                            </form>
                            <button class='btn' type='button' onclick='editTicket(".$row['ticket_id'].", ".$row['passenger_id'].", ".$row['flight_id'].", ".$row['user_id'].", \"".$row['seat_no']."\", ".$row['cost'].", \"".$row['class']."\")'>
                                <i class='text-primary fa fa-edit'></i>
                            </button>
                        </td>
                    </tr>
                    ";
                    $cnt++;
                }
                ?>
            </tbody>
        </table>

        <!-- Update Form Modal -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form action="booked.php" method="post">
                    <input type="hidden" name="ticket_id" id="update_ticket_id">
                    <div class="form-group">
                        <label for="passenger_id">Passenger ID</label>
                        <input type="text" class="form-control" name="passenger_id" id="update_passenger_id" required>
                    </div>
                    <div class="form-group">
                        <label for="flight_id">Flight ID</label>
                        <input type="text" class="form-control" name="flight_id" id="update_flight_id" required>
                    </div>
                    <div class="form-group">
                        <label for="user_id">User ID</label>
                        <input type="text" class="form-control" name="user_id" id="update_user_id" required>
                    </div>
                    <div class="form-group">
                        <label for="seat_no">Seat No</label>
                        <input type="text" class="form-control" name="seat_no" id="update_seat_no" required>
                    </div>
                    <div class="form-group">
                        <label for="cost">Cost</label>
                        <input type="text" class="form-control" name="cost" id="update_cost" required>
                    </div>
                    <div class="form-group">
                        <label for="class">Class</label>
                        <input type="text" class="form-control" name="class" id="update_class" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_ticket">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function editTicket(ticket_id, passenger_id, flight_id, user_id, seat_no, cost, class_name) {
    document.getElementById('update_ticket_id').value = ticket_id;
    document.getElementById('update_passenger_id').value = passenger_id;
    document.getElementById('update_flight_id').value = flight_id;
    document.getElementById('update_user_id').value = user_id;
    document.getElementById('update_seat_no').value = seat_no;
    document.getElementById('update_cost').value = cost;
    document.getElementById('update_class').value = class_name;
    document.getElementById('updateModal').style.display = "block";
}

document.querySelectorAll('.close').forEach(function(element) {
    element.onclick = function() {
        document.getElementById('updateModal').style.display = "none";
    }
});

window.onclick = function(event) {
    if (event.target == document.getElementById('updateModal')) {
        document.getElementById('updateModal').style.display = "none";
    }
}
</script>