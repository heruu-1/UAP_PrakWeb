<?php 
require "config.php";

if(isset($_GET["id"])){
    $id = $_GET["id"];
    $user = query("SELECT * FROM infologin WHERE id=$id")[0];
}

if(isset($_POST["submit"])){
    if(edit($_POST) > 0){
        echo "<script>
                alert('Data berhasil diubah');
                document.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diubah');
                document.location.href = 'dashboard.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
    <h2 class="h2">Edit Data</h2>
    <form action="" method="post" class="form1">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label for="nama">Nama</label><br>
        <input type="text" name="nama" id="nama" class="input1" value="<?php echo $user['nama']; ?>"><br><br>
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" class="input1" value="<?php echo $user['email']; ?>"><br><br>
        <label for="no_hp">No Handphone</label><br>
        <input type="text" name="no_hp" id="no_hp" class="input1" value="<?php echo $user['no_hp']; ?>"><br><br>
        <button type="submit" name="submit" class="button1">Edit</button>
    </form>
</body>
</html>
