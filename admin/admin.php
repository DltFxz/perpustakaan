<?php
require '../config/koneksi.php';
session_start();

// if ($_SESSION['nama'] == null) {
//     header('location:../index.php?pesan=login_dulu');
//     exit();
// }

if ($_SESSION['role'] != 'admin') {
    session_destroy();
    header('location:../index.php?pesan=lu_tuh_cuma_member');
    exit();
}

$buku = mysqli_query($conn, "SELECT COUNT(*) as total_buku from buku");
$anggota = mysqli_query($conn, "SELECT COUNT(*) as total_anggota from anggota");

$total_buku = mysqli_fetch_assoc($buku);
$total_anggota = mysqli_fetch_assoc($anggota);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Perpustakaan - SMKN 1 Pringgabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar Admin -->
    <nav class="bg-blue-700 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Nama -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i data-feather="shield" class="text-white" width="20" height="20"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-white">Admin Perpustakaan</span>
                        <p class="text-xs text-blue-200">SMKN 1 Pringgabaya</p>
                    </div>
                </div>

                <!-- Admin Profile -->
                <div class="flex items-center space-x-3">
                    <button class="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i data-feather="bell" class="text-white" width="18" height="18"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="flex items-center space-x-2">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-medium text-white"><?= $_SESSION['nama'] ?></p>
                            <p class="text-xs text-blue-200">Admin</p>
                        </div>
                        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                            <i data-feather="user" class="text-white" width="18" height="18"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg min-h-screen hidden md:block">
            <div class="p-4">
                <!-- Profile Admin -->
                <div class="bg-blue-50 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 w-12 h-12 rounded-xl flex items-center justify-center">
                            <i data-feather="user" class="text-white" width="20" height="20"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800"><?= $_SESSION['nama'] ?></p>
                            <p class="text-xs text-blue-600">Admin Perpustakaan</p>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="border-t border-gray-200 mt-6 pt-4">
                    <a type="button" href="../config/logout.php" class="flex items-center gap-3 text-red-600 hover:bg-red-50 px-4 py-3 rounded-lg transition-colors w-full">
                        <i data-feather="log-out" width="18" height="18"></i>
                        <span class="text-sm font-medium">Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 mb-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Dashboard Admin</h2>
                        <p class="text-blue-100 text-sm">Selamat datang, <?= $_SESSION['nama'] ?>! Berikut ringkasan perpustakaan SMKN 1 Pringgabaya.</p>
                    </div>
                    <div class="text-sm bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg mt-4 md:mt-0">
                        <i data-feather="calendar" class="inline mr-2" width="14" height="14"></i>
                        Jumat, 14 Februari 2026
                    </div>
                </div>
            </div>

            <!-- Statistik Cards - Sederhana -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Anggota</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $total_anggota['total_anggota'] ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i data-feather="users" class="text-blue-600" width="20" height="20"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Buku</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $total_buku['total_buku'] ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i data-feather="book" class="text-green-600" width="20" height="20"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Admin -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-blue-500 group">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-600 w-14 h-14 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-700 transition-colors">
                            <i data-feather="users" class="text-white" width="24" height="24"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Kelola Anggota</h3>
                        <p class="text-xs text-gray-500 mt-1">Tambah, edit, hapus</p>
                    </div>
                </div>

                <a href="../page/buku.php">
                    <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-blue-500 group">
                        <div class="flex flex-col items-center text-center">
                            <div class="bg-blue-600 w-14 h-14 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-700 transition-colors">
                                <i data-feather="book" class="text-white" width="24" height="24"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800">Kelola Buku</h3>
                            <p class="text-xs text-gray-500 mt-1">Tambah, edit, hapus</p>
                        </div>
                    </div>
                </a>

                <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all cursor-pointer border-2 border-transparent hover:border-blue-500 group">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-600 w-14 h-14 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-700 transition-colors">
                            <i data-feather="user" class="text-white" width="24" height="24"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Profil Admin</h3>
                        <p class="text-xs text-gray-500 mt-1">Ubah data admin</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Perpustakaan -->
            <div class="bg-white rounded-xl shadow-md p-5">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-feather="info" width="18" height="18" class="text-blue-600"></i>
                    Informasi Perpustakaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <i data-feather="clock" class="text-blue-600" width="16" height="16"></i>
                        <span>Jam Operasional: 07.00 - 16.00 WITA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-feather="calendar" class="text-blue-600" width="16" height="16"></i>
                        <span>Tahun Ajaran: 2025/2026</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-feather="map-pin" class="text-blue-600" width="16" height="16"></i>
                        <span>SMKN 1 Pringgabaya, Lombok Timur</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2">
        <div class="flex justify-around">
            <button class="p-2 text-blue-600">
                <i data-feather="home" width="20" height="20"></i>
            </button>
            <button class="p-2 text-gray-600">
                <i data-feather="users" width="20" height="20"></i>
            </button>
            <button class="p-2 text-gray-600">
                <i data-feather="book" width="20" height="20"></i>
            </button>
            <button class="p-2 text-gray-600">
                <i data-feather="bar-chart-2" width="20" height="20"></i>
            </button>
            <button class="p-2 text-gray-600">
                <i data-feather="settings" width="20" height="20"></i>
            </button>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>

</html>