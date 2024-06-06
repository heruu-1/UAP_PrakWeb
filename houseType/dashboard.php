<?php  
require "config.php";
$users = query("SELECT * FROM infologin ORDER BY id ASC");

if(isset($_POST["cari"])){
    $users = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipe Rumah</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="body">
    <h1 class="h1">Kategori</h1>
    <form action="" method="post" class="form">
        <input type="text" name="keyword" size="40" autofocus placeholder="Masukkan keyword" autocomplete="off"
            class="input">
        <button type="submit" name="cari" class="button">Cari!</button>
        <br><br>
        <a href="add.php">Tambah User</a>
        <br>
    </form>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>id</th>
            <th>nama</th>
            <th>email</th>
            <th>no handphone</th>
            <th>aksi</th>
        </tr>
        <?php $i = 1; ?>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $user['nama']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['no_hp']; ?></td>
            <td>
                <button><a href="edit.php?id=<?php echo $user['id']; ?>">Edit</a></button>
                <button><a href="delete.php?id=<?php echo $user['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus?');">Delete</a>
                </button>
            </td>
        </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
</body>

</html>