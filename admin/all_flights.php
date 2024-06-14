<?php include_once 'header.php'; ?>
<?php include_once 'footer.php';
require '../helpers/init_conn_db.php';?>

<?php
if (isset($_POST['del_flight']) && isset($_SESSION['adminId'])) {
    $flight_id = $_POST['flight_id'];
    $sql = 'DELETE FROM Flight WHERE flight_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $flight_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'all_flights.php';</script>");
        exit();
    }
}

if (isset($_POST['update_flight']) && isset($_SESSION['adminId'])) {
    $flight_id = $_POST['flight_id'];
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $airline = $_POST['airline'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];

    $sql = 'UPDATE Flight SET arrivale=?, departure=?, source=?, Destination=?, airline=?, Seats=?, Price=? WHERE flight_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssiid', $arrival, $departure, $source, $destination, $airline, $seats, $price, $flight_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'all_flights.php';</script>");
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
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
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
    <?php if (isset($_SESSION['adminId'])) { ?>
    <div class="container-md mt-2">
        <h1 class="display-4 text-center text-secondary">FLIGHT LIST</h1>
        <button class="btn btn-success mb-2" type="button" onclick="window.location.href='flight.php'">Add Flight</button>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Arrival</th>
                    <th scope="col">Departure</th>
                    <th scope="col">Source</th>
                    <th scope="col">Destination</th>
                    <th scope="col">Airline</th>
                    <th scope="col">Seats</th>
                    <th scope="col">Price</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT * FROM Flight ORDER BY flight_id DESC';
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <tr class='text-center'>
                        <td scope='row'>
                            <a href='pass_list.php?flight_id=" . $row['flight_id'] . "'>" . $row['flight_id'] . "</a>
                        </td>
                        <td>" . $row['arrivale'] . "</td>
                        <td>" . $row['departure'] . "</td>
                        <td>" . $row['source'] . "</td>
                        <td>" . $row['Destination'] . "</td>
                        <td>" . $row['airline'] . "</td>
                        <td>" . $row['Seats'] . "</td>
                        <td>Rp" . number_format($row['Price'], 0, ',', '.') . "</td>
                        <td>
                            <form action='all_flights.php' method='post' style='display:inline-block;'>
                                <input name='flight_id' type='hidden' value=" . $row['flight_id'] . ">
                                <button class='btn' type='submit' name='del_flight'>
                                    <i class='text-danger fa fa-trash'></i>
                                </button>
                            </form>
                            <button class='btn' onclick='openModal(" . $row['flight_id'] . ")'>
                                <i class='text-warning fa fa-edit'></i>
                            </button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</main>

<!-- Update Flight Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h1 class="display-4 text-center text-secondary">UPDATE FLIGHT</h1>
        <form id="updateForm" action="all_flights.php" method="post">
            <input type="hidden" id="flight_id" name="flight_id" value="">
            <div class="form-group">
                <label for="arrival">Arrival</label>
                <input type="text" class="form-control" id="arrival" name="arrival" required>
            </div>
            <div class="form-group">
                <label for="departure">Departure</label>
                <input type="text" class="form-control" id="departure" name="departure" required>
            </div>
            <div class="form-group">
                <label for="source">Source</label>
                <input type="text" class="form-control" id="source" name="source" required>
            </div>
            <div class="form-group">
                <label for="destination">Destination</label>
                <input type="text" class="form-control" id="destination" name="destination" required>
            </div>
            <div class="form-group">
                <label for="airline">Airline</label>
                <input type="text" class="form-control" id="airline" name="airline" required>
            </div>
            <div class="form-group">
                <label for="seats">Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <button type="submit" class="btn btn-primary" name="update_flight">Update Flight</button>
        </form>
    </div>
</div>

<script>
function openModal(flight_id) {
    document.getElementById("updateModal").style.display = "block";
    // Fetch existing flight details and populate the form
    fetch(`get_flight_details.php?flight_id=${flight_id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('flight_id').value = data.flight_id;
            document.getElementById('arrival').value = data.arrivale;
            document.getElementById('departure').value = data.departure;
            document.getElementById('source').value = data.source;
            document.getElementById('destination').value = data.Destination;
            document.getElementById('airline').value = data.airline;
            document.getElementById('seats').value = data.Seats;
            document.getElementById('price').value = data.Price;
        });
}

function closeModal() {
    document.getElementById("updateModal").style.display = "none";
}
</script>
