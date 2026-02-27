<?php
require '../config/koneksi.php';
session_start();
if (!isset($_SESSION['id_anggota'])) {
    header('Location: ../index.php');
    exit();
}

$sql = getData($conn, 'buku');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan SMKN 1 Pringgabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-gradient-to-r from-blue-700 to-blue-800 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i data-feather="book-open" class="text-white" width="20"></i>
                    </div>
                    <span class="font-semibold text-lg">Pinjam Buku</span>
                </div>

                <!-- User Info (STATIS) -->
                <div class="flex items-center gap-4">
                    <span class="text-sm hidden md:block">Halo, <span class="font-semibold"><?= $_SESSION['nama'] ?></span> (<?= $_SESSION['role'] ?>)</span>
                    <a href="../main.php" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                        <i data-feather="arrow-left" width="14"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mx-auto px-4 py-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📚 Daftar Buku</h1>
                <p class="text-gray-500 text-sm mt-1">Pilih buku yang ingin kamu pinjam</p>
            </div>
            <div class="mt-3 md:mt-0">
                <a href="pinjaman.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-feather="book" width="16"></i>
                    Pinjaman Saya
                </a>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="mb-8">
            <div class="relative">
                <input type="text" id="search" placeholder="Cari judul buku..."
                    class="w-full md:w-96 pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i data-feather="search" class="absolute left-3 top-3.5 text-gray-400" width="18"></i>
            </div>
        </div>

        <!-- GRID BUKU -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="bukuGrid">

            <?php
            while ($data = mysqli_fetch_assoc($sql)):
            ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition buku-item">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden">
                        <img src="../uploads/<?= $data['sampul'] ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-800 mb-1"><?= $data['judul'] ?></h3>
                        <p class="text-gray-600 text-sm mb-2">
                            <i data-feather="home" width="12" class="inline mr-1"></i> <?= $data['penerbit'] ?>
                        </p>
                        <p class="text-gray-400 text-xs mb-4">
                            <i data-feather="calendar" width="12" class="inline mr-1"></i> <?= $data['tggl_terbit'] ?>
                        </p>
                        <a href="proses.php?id_buku=<?= $data['id_buku'] ?>" onclick="return confirm('Pinjam Buku <?= $data['judul']?>?')" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg flex items-center justify-center gap-2 transition">
                            <i data-feather="book-open" width="16"></i>
                            Pinjam Buku
                        </a>
                    </div>
                </div>
            <?php
            endwhile;
            ?>

            <!-- BUKU 3 - SEDANG DIPINJAM -->
            <!-- <div class="bg-white rounded-xl shadow-md overflow-hidden buku-item">
                <div class="h-48 bg-gradient-to-br from-yellow-400 to-orange-500 overflow-hidden relative">
                    <img src="https://via.placeholder.com/400x300/f59e0b/ffffff?text=JavaScript" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                        <i data-feather="clock" width="12"></i> Dipinjam
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-800 mb-1">JavaScript Dasar</h3>
                    <p class="text-gray-600 text-sm mb-2">
                        <i data-feather="home" width="12" class="inline mr-1"></i> Informatika
                    </p>
                    <p class="text-gray-400 text-xs mb-4">
                        <i data-feather="calendar" width="12" class="inline mr-1"></i> 10 Mar 2024
                    </p>
                    <button disabled class="w-full bg-gray-200 text-gray-500 py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                        <i data-feather="x-circle" width="16"></i>
                        Sedang Dipinjam
                    </button>
                </div>
            </div> -->


            <!-- PESAN KALAU TIDAK ADA BUKU (HIDDEN) -->
            <div id="emptyMessage" class="bg-white p-12 text-center rounded-lg shadow hidden">
                <i data-feather="book" width="48" height="48" class="mx-auto text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada buku yang ditemukan</p>
            </div>
        </div>

        <!-- SCRIPT -->
        <script>
            feather.replace();

            // SEARCH FUNCTION - UI ONLY
            document.getElementById('search').addEventListener('keyup', function() {
                let searchValue = this.value.toLowerCase();
                let bukuItems = document.querySelectorAll('.buku-item');
                let visibleCount = 0;

                bukuItems.forEach(item => {
                    let judul = item.querySelector('h3').textContent.toLowerCase();
                    if (judul.includes(searchValue)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide empty message
                let emptyMessage = document.getElementById('emptyMessage');
                if (visibleCount === 0) {
                    emptyMessage.classList.remove('hidden');
                } else {
                    emptyMessage.classList.add('hidden');
                }
            });
        </script>

</body>

</html>