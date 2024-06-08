<?php include_once 'header.php'; ?>
<?php include_once 'footer.php'; ?>
<?php require '../helpers/init_conn_db.php'; ?>

<?php
// Handle Delete Operation
if (isset($_POST['del_airlines']) && isset($_SESSION['adminId'])) {
    $airline_id = $_POST['airline_id'];
    $sql = 'DELETE FROM airline WHERE airline_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $airline_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'list_airlines.php';</script>");
        exit();
    }
}

// Handle Update Operation
if (isset($_POST['update_airlines']) && isset($_SESSION['adminId'])) {
    $airline_id = $_POST['airline_id'];
    $name = $_POST['name'];
    $seats = $_POST['seats'];
    $sql = 'UPDATE airline SET name=?, seats=? WHERE airline_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'sii', $name, $seats, $airline_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'list_airlines.php';</script>");
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
    <?php if(isset($_SESSION['adminId'])) { ?>
      <div class="container-md mt-2">
        <h1 class="display-4 text-center text-secondary">AIRLINES LIST</h1>
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Seats</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            
            <?php
            $cnt=1;
            $sql = 'SELECT * FROM airline ORDER BY airline_id ASC';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
              echo "
              <tr class='text-center'>
                <td scope='row'>".$cnt."</td>
                <td>".$row['name']."</td>
                <td>".$row['seats']."</td>
                <td>
                <form action='list_airlines.php' method='post' style='display:inline-block;'>
                  <input name='airline_id' type='hidden' value=".$row['airline_id'].">
                  <button class='btn' type='submit' name='del_airlines'>
                  <i class='text-danger fa fa-trash'></i></button>
                </form>
                <button class='btn' type='button' onclick='editAirline(".$row['airline_id'].", \"".$row['name']."\", ".$row['seats'].")'>
                <i class='text-primary fa fa-edit'></i></button>
                </td>
              </tr>
              ";
           $cnt++; }
            ?>
            
          </tbody>
        </table>

        <!-- Update Form Modal -->
        <div id="updateModal" class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <form action="list_airlines.php" method="post">
              <input type="hidden" name="airline_id" id="update_airline_id">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="update_name" required>
              </div>
              <div class="form-group">
                <label for="seats">Seats</label>
                <input type="number" class="form-control" name="seats" id="update_seats" required>
              </div>
              <button type="submit" class="btn btn-primary" name="update_airlines">Update</button>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>
</main>

<script>
function editAirline(id, name, seats) {
  document.getElementById('update_airline_id').value = id;
  document.getElementById('update_name').value = name;
  document.getElementById('update_seats').value = seats;
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
