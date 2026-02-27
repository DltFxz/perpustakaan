<?php
session_start();
require '../config/koneksi.php';


$id_buku = $_GET['id_buku'];

$cek_pinjam = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_buku = '$id_buku' AND status = 'dipinjam'");
if (mysqli_num_rows($cek_pinjam) > 0) {
    echo "<script>
        alert('Buku tidak bisa dihapus karena sedang dipinjam!');
        window.location.href = '../admin/perpustakawan.php';
    </script>";
    exit();
}


$query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
$buku = mysqli_fetch_assoc($query);

if (!$buku) {
    echo "<script>
        alert('Buku tidak ditemukan!');
        window.location.href = '../admin/perpustakawan.php';
    </script>";
    exit();
}

if (!empty($buku['sampul']) && file_exists("../uploads/" . $buku['sampul'])) {
    unlink("../uploads/" . $buku['sampul']); 
}

$hapus = mysqli_query($conn, "DELETE FROM buku WHERE id_buku = '$id_buku'");

if ($hapus) {
    echo "<script>
        alert('Buku \"" . $buku['judul'] . "\" berhasil dihapus!');
        window.location.href = '../admin/perpustakawan.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus buku: " . mysqli_error($conn) . "');
        window.location.href = 'buku.php';
    </script>";
}
