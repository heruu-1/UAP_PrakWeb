<?php 
$conn=mysqli_connect("localhost", "root", "", "weblogin");

function query($query){
    global $conn;
    $result=mysqli_query($conn, $query);
    $rows=[];

    while($row=mysqli_fetch_assoc($result)){
        $rows[]=$row;
    }
    return $rows;
}

function tambah($data){
    global $conn;
    $nama=htmlspecialchars($data['nama']);
    $email=htmlspecialchars($data['email']);
    $no_hp=htmlspecialchars($data['no_hp']);

    $query="INSERT INTO infologin VALUES('', '$nama', '$email', '$no_hp')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapus($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM infologin WHERE id=$id");
    return mysqli_affected_rows($conn);
}

function edit($data){
    global $conn;
    $id=$data['id'];
    $nama=htmlspecialchars($data['nama']);
    $email=htmlspecialchars($data['email']);
    $no_hp=htmlspecialchars($data['no_hp']);

    $query="UPDATE infologin SET nama='$nama', email='$email', no_hp='$no_hp' WHERE id=$id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function cari($keyword){
    $query="SELECT * FROM infologin WHERE nama LIKE '%$keyword%' OR email LIKE '%$keyword%' OR no_hp LIKE '%$keyword%'";
    return query($query);
}
?>