<?php include_once 'header.php'; ?>
<?php include_once 'footer.php'; ?>
<?php require '../helpers/init_conn_db.php'; ?>

<?php
// Handle Delete Operation
if (isset($_POST['del_admins']) && isset($_SESSION['adminId'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];
    if ($role === 'admin') {
        $sql = 'DELETE FROM admin WHERE admin_id=?';
    } else {
        $sql = 'DELETE FROM users WHERE user_id=?';
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'manage_user.php';</script>");
        exit();
    }
}

// Handle Update Operation
if (isset($_POST['update_admins']) && isset($_SESSION['adminId'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    if ($role === 'admin') {
        $sql = 'UPDATE admin SET admin_uname=?, admin_email=? WHERE admin_id=?';
    } else {
        $sql = 'UPDATE users SET username=?, email=? WHERE user_id=?';
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $email, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'manage_user.php';</script>");
        exit();
    }
}

// Handle Add Operation for Admins
if (isset($_POST['add_admins']) && isset($_SESSION['adminId'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    if ($role === 'admin') {
        $sql = 'INSERT INTO admin (admin_uname, admin_email, admin_password) VALUES (?, ?, ?)';
    } else {
        $sql = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'manage_user.php';</script>");
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
    <div class="container-md mt-2">
        <h1 class="display-4 text-center text-secondary">USERS</h1>
        <button class="btn btn-success mb-2" type="button" onclick="openAddModal()">Add Admin/User</button>
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
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                $sql = '(SELECT admin_id AS id, admin_uname AS username, admin_email AS email, "admin" AS role FROM admin)
                        UNION
                        (SELECT user_id AS id, username, email, "user" AS role FROM users)
                        ORDER BY id ASC';
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <tr class='text-center'>
                        <td>".$row['username']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['role']."</td>
                        <td>
                            <form action='manage_user.php' method='post' style='display:inline-block;'>
                                <input name='id' type='hidden' value='".$row['id']."'>
                                <input name='role' type='hidden' value='".$row['role']."'>
                                <button class='btn' type='submit' name='del_admins'>
                                    <i class='text-danger fa fa-trash'></i>
                                </button>
                            </form>
                            <button class='btn' type='button' onclick='editAdmin(".$row['id'].", \"".$row['username']."\", \"".$row['email']."\", \"".$row['role']."\")'>
                                <i class='text-primary fa fa-edit'></i>
                            </button>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>

        <!-- Update Form Modal -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h1 class="display-4 text-center text-secondary">UPDATE</h1>
                <form action="manage_user.php" method="post">
                    <input type="hidden" name="id" id="update_id">
                    <input type="hidden" name="role" id="update_role">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="update_username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="update_email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_admins">Update</button>
                </form>
            </div>
        </div>

        <!-- Add Admin/User Form Modal -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h1 class="display-4 text-center text-secondary">ADD ADMIN/USER</h1>
                <form action="manage_user.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="add_username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="add_email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="add_password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="add_role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_admins">Add</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = "block";
}

function editAdmin(id, username, email, role) {
    document.getElementById('update_id').value = id;
    document.getElementById('update_username').value = username;
    document.getElementById('update_email').value = email;
    document.getElementById('update_role').value = role;
    document.getElementById('updateModal').style.display = "block";
}

document.querySelectorAll('.close').forEach(function(element) {
    element.onclick = function() {
        document.getElementById('updateModal').style.display = "none";
        document.getElementById('addModal').style.display = "none";
    }
});

window.onclick = function(event) {
    if (event.target == document.getElementById('updateModal')) {
        document.getElementById('updateModal').style.display = "none";
    }
    if (event.target == document.getElementById('addModal')) {
        document.getElementById('addModal').style.display = "none";
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
  const rows = document.querySelectorAll('#userTable tr');
  let displayedCount = 0;

  rows.forEach(function(row) {
    const username = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
    if (username.indexOf(searchValue) > -1) {
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
