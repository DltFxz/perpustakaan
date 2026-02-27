<?php 
$conn = mysqli_connect('localhost', 'root', '', 'perpustakaan');

function getData($conn, $tabel){
    $query = mysqli_query($conn, "SELECT * FROM $tabel");
    return $query;
}

function totalData($conn, $tabel){
    $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM $tabel");
    $data = mysqli_fetch_assoc($query);
    return $data['total'];
}
?>