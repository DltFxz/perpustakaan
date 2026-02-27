<?php
session_start();
require '../config/koneksi.php';

// CEK LOGIN
if (!isset($_SESSION['id_anggota'])) {
    header('Location: ../login.php');
    exit();
}

// AMBIL ID DARI URL
$id_pinjam = $_GET['id'];
$tanggal_sekarang = date('Y-m-d');

// UPDATE STATUS JADI KEMBALI
mysqli_query($conn, "UPDATE peminjaman 
                      SET status = 'kembali', 
                          tanggal_kembali = '$tanggal_sekarang' 
                      WHERE id_pinjam = '$id_pinjam'");

// LANGSUNG KEMBALI KE HALAMAN PINJAMAN
header('Location: pinjaman.php');
