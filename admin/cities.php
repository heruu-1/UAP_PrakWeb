<?php include_once 'header.php'; ?>
<?php include_once 'footer.php'; ?>
<?php require '../helpers/init_conn_db.php'; ?>

<?php
// Handle Delete Operation
if (isset($_POST['del_city']) && isset($_SESSION['adminId'])) {
    $city = $_POST['city'];
    $sql = 'DELETE FROM cities WHERE city=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 's', $city);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'cities.php';</script>");
        exit();
    }
}

// Handle Update Operation
if (isset($_POST['update_city']) && isset($_SESSION['adminId'])) {
    $old_city = $_POST['old_city'];
    $new_city = $_POST['new_city'];
    $sql = 'UPDATE cities SET city=? WHERE city=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'ss', $new_city, $old_city);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'cities.php';</script>");
        exit();
    }
}

// Handle Add Operation
if (isset($_POST['add_city']) && isset($_SESSION['adminId'])) {
    $new_city = $_POST['new_city'];
    $sql = 'INSERT INTO cities (city) VALUES (?)';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 's', $new_city);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'cities.php';</script>");
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
        <h1 class="display-4 text-center text-secondary">CITIES LIST</h1>
        
        <!-- Add City Form -->
        <form action="cities.php" method="post" class="mb-4">
          <div class="form-group">
            <label for="new_city">Add New City</label>
            <input type="text" class="form-control" name="new_city" id="new_city" required>
          </div>
          <button type="submit" class="btn btn-primary" name="add_city">Add</button>
        </form>

        <!-- Search and Entries Display -->
        <div class="d-flex justify-content-between mb-3">
          <div>
            <label>Show 
              <select id="entries" class="custom-select" style="width: auto;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select> entries
            </label>
          </div>
          <div>
            <label>Search:
              <input type="text" id="search" class="form-control" style="display: inline-block; width: auto;">
            </label>
          </div>
        </div>

        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">City</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody id="cityTable">
            
            <?php
            $cnt = 1;
            $sql = 'SELECT * FROM cities ORDER BY city ASC';
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "
                  <tr class='text-center'>
                    <td scope='row'>".$cnt."</td>
                    <td>".$row['city']."</td>
                    <td>
                    <form action='cities.php' method='post' style='display:inline-block;'>
                      <input name='city' type='hidden' value='".$row['city']."'>
                      <button class='btn' type='submit' name='del_city'>
                      <i class='text-danger fa fa-trash'></i></button>
                    </form>
                    <button class='btn' type='button' onclick='editCity(\"".$row['city']."\")'>
                    <i class='text-primary fa fa-edit'></i></button>
                    </td>
                  </tr>
                  ";
                $cnt++; 
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_close($conn);
            ?>
            
          </tbody>
        </table>

        <!-- Update Form Modal -->
        <div id="updateModal" class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <h1 class="display-4 text-center text-secondary">UPDATE CITY</h1>
            <form action="cities.php" method="post">
              <input type="hidden" name="old_city" id="update_old_city">
              <div class="form-group">
                <label for="new_city">City</label>
                <input type="text" class="form-control" name="new_city" id="update_new_city" required>
              </div>
              <button type="submit" class="btn btn-primary" name="update_city">Update</button>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>
</main>

<script>
function editCity(city) {
  document.getElementById('update_old_city').value = city;
  document.getElementById('update_new_city').value = city;
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

// Live Search Functionality
document.getElementById('search').addEventListener('keyup', function() {
  filterTable();
});

// Entries Display Functionality
document.getElementById('entries').addEventListener('change', function() {
  filterTable();
});

function filterTable() {
  const searchValue = document.getElementById('search').value.toLowerCase();
  const entriesValue = parseInt(document.getElementById('entries').value);
  const rows = document.querySelectorAll('#cityTable tr');
  let displayedCount = 0;

  rows.forEach(function(row) {
    const city = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
    if (city.indexOf(searchValue) > -1) {
      if (displayedCount < entriesValue) {
        row.style.display = '';
        displayedCount++;
      } else {
        row.style.display = 'none';
      }
    } else {
      row.style.display = 'none';
    }
  });
}

// Initialize display
document.getElementById('entries').dispatchEvent(new Event('change'));
</script>
