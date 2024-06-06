<?php 
require "config.php";
$conn = mysqli_connect("localhost", "root", "", "weblogin");

if(isset($_POST["submit"])){
    if(tambah($_POST) > 0){
        echo "<script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan');
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
    <title>Tambah Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
    <h2 class="h2">Tambah Data</h2>
    <form action="" method="post" class="form1">
        <label for="nama">Nama</label><br>
        <input type="text" name="nama" id="nama" class="input1"><br><br>
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" class="input1"><br><br>
        <label for="no_hp">No Handphone</label><br>
        <input type="text" name="no_hp" id="no_hp" class="input1"><br><br>
        <button type="submit" name="submit" class="button1">Tambah</button>
    </form>
</body>
</html>
