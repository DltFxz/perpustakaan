<?php
require '../config/koneksi.php';
session_start();

$sql = getData($conn, 'buku');

$id_anggota = $_SESSION['id_anggota'];
$id_buku = $_GET['id_buku'];

$cek = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_anggota='$id_anggota' AND id_buku='$id_buku' AND status='dipinjam'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Kamu udah pinjam buku ini!'); window.location.href='pinjam.php';</script>";
    exit();
}

    $tgl_pinjam = date('y-m-d');
    $tgl_kembali = date('y-m-d', strtotime('+5 days'));

    $query = mysqli_query($conn, "INSERT INTO peminjaman(id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status) VALUES('$id_anggota','$id_buku','$tgl_pinjam','$tgl_kembali','dipinjam')");

    if($query){
    echo "<script>
        alert('Buku berhasil dipinjam!');
        window.location.href='pinjaman.php';
    </script>";
    }else {
    echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.location.href='pinjam.php';</script>";
}


?>