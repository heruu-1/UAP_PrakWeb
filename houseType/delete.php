<?php
require "config.php";
if(isset($_GET["id"])){
    $id=$_GET["id"];
    if(hapus($id)>0){
        echo "<script>
            alert('Data berhasil dihapus');
            document.location.href='dashboard.php';
        </script>";
    }else{
        echo "<script>
            alert('Data gagal dihapus');
            document.location.href='dashboard.php';
        </script>";
    }
}