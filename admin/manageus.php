<?php include_once 'header.php'; ?>
<?php include_once 'footer.php'; ?>
<?php require '../helpers/init_conn_db.php'; ?>

<?php
// Handle Delete Operation
if (isset($_POST['del_users']) && isset($_SESSION['adminId'])) {
    $user_id = $_POST['user_id'];
    $sql = 'DELETE FROM users WHERE user_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'manageus.php';</script>");
        exit();
    }
}

// Handle Update Operation
if (isset($_POST['update_users']) && isset($_SESSION['adminId'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sql = 'UPDATE users SET username=?, email=? WHERE user_id=?';
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $email, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo("<script>location.href = 'manageus.php';</script>");
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
        <h1 class="display-4 text-center text-secondary">USERS LIST</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Password</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                $sql = 'SELECT * FROM users ORDER BY user_id ASC';
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <tr class='text-center'>
                        <td scope='row'>".$cnt."</td>
                        <td>".$row['username']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['password']."</td>
                        <td>
                            <form action='manageus.php' method='post' style='display:inline-block;'>
                                <input name='user_id' type='hidden' value='".$row['user_id']."'>
                                <button class='btn' type='submit' name='del_users'>
                                    <i class='text-danger fa fa-trash'></i>
                                </button>
                            </form>
                            <button class='btn' type='button' onclick='editUser(".$row['user_id'].", \"".$row['username']."\", \"".$row['email']."\")'>
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
                <form action="manageus.php" method="post">
                    <input type="hidden" name="user_id" id="update_user_id">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="update_username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="update_email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_users">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function editUser(id, username, email) {
    document.getElementById('update_user_id').value = id;
    document.getElementById('update_username').value = username;
    document.getElementById('update_email').value = email;
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