<?php
require 'config/koneksi.php';

session_start();

// if($_SESSION['nama'] == null){
//     header("location:index.php?Login");
// }

$qwr = getData($conn, 'buku');

$id_anggota = $_SESSION['id_anggota'];

$total_pinjaman = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE id_anggota='$id_anggota'");
$total = mysqli_fetch_assoc($total_pinjaman);

$sedang_dipinjam = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman 
                                         WHERE id_anggota='$id_anggota' 
                                         AND status='dipinjam'");
$aktif = mysqli_fetch_assoc($sedang_dipinjam);


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Perpustakawan - SMKN 1 PRINGGABAYA</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar menu styles */
        .nav-menu {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .nav-menu.active {
            background-color: rgba(255, 255, 255, 0.25);
            border-bottom: 2px solid white;
        }

        .mobile-menu {
            transition: all 0.3s ease;
        }

        /* Table styles */
        .table-container {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            padding: 1rem 1rem;
        }

        table tbody tr {
            transition: all 0.2s ease;
        }

        table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        /* Button styles */
        .action-button {
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-button:hover {
            background-color: currentColor;
            opacity: 0.8;
        }

        .stat-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .add-button {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }

        /* Modal styles */
        .modal-content {
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cover image style */
        .book-cover {
            width: 3rem;
            height: 3.5rem;
            object-fit: cover;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="min-h-screen">

    <!-- Navbar Section -->
    <nav class="bg-blue-700/90 backdrop-blur-md shadow-xl sticky top-0 z-50 border-b border-white/10">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Nama -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm border border-white/10">
                        <i data-feather="shield" class="text-white" width="20" height="20"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-white text-lg tracking-wide">SMKN 1 PRINGGABAYA</span>
                        <p class="text-xs text-blue-200 font-light">Perpustakaan Digital</p>
                    </div>
                </div>

                <!-- Menu Navigasi Desktop -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href=""
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="page/buku.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="book" width="18" height="18"></i>
                        <span class="text-sm font-medium">Buku</span>
                    </a>
                    <a href="anggota/pinjaman.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="book-open" width="18" height="18"></i>
                        <span class="text-sm font-medium">Pinjaman</span>
                    </a>
                </div>

                <!-- Profile & Logout -->
                <div class="flex items-center space-x-3">
                    <div
                        class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/10">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-medium text-white"><?= $_SESSION['nama'] ?></p>
                            <p class="text-xs text-blue-200"><?= $_SESSION['role'] ?></p>
                        </div>
                        <div
                            class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center border-2 border-white/30">
                            <i data-feather="user" class="text-white" width="18" height="18"></i>
                        </div>
                    </div>

                    <!-- Tombol Menu Mobile -->
                    <button class="md:hidden p-2 hover:bg-white/20 rounded-lg transition-colors" id="menuToggle">
                        <i data-feather="menu" class="text-white" width="24" height="24"></i>
                    </button>

                    <a href="config/logout.php"
                        class="flex items-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-white px-3 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i data-feather="log-out" width="18" height="18"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu (Hidden by default) -->
            <div class="md:hidden hidden mobile-menu pb-4" id="mobileMenu">
                <div class="flex flex-col space-y-2">
                    <a href="dashboard.php"
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="buku.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="book" width="18" height="18"></i>
                        <span class="text-sm font-medium">Buku</span>
                    </a>
                    <a href="pinjaman.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="book-open" width="18" height="18"></i>
                        <span class="text-sm font-medium">Pinjaman</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 mb-8 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Howdy <?= $_SESSION['nama'] ?>! 👋</h2>
                    <p class="text-blue-100 text-sm">Selamat datang di Perpustakaan SMKN 1 PRINGGABAYA</p>
                </div>
                <!-- Statistik -->
                <div class="flex gap-3 mt-4 md:mt-0">
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-center">
                        <p class="text-xs text-blue-200">Dipinjam</p>
                        <p class="text-xl font-bold"><?= $aktif['total'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Grid - Hanya 3 Menu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Menu 1: Cari Buku -->
            <a href="page/buku.php" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-blue-500 group">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-600 w-14 h-14 rounded-xl flex items-center justify-center group-hover:bg-blue-700 transition-colors">
                        <i data-feather="book" class="text-white" width="24" height="24"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Lihat Buku</h3>
                        <p class="text-sm text-gray-500">Temukan koleksi buku</p>
                    </div>
                </div>
            </a>

            <!-- Menu 2: Peminjaman -->
            <a href="anggota/pinjaman.php" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-blue-500 group relative">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-600 w-14 h-14 rounded-xl flex items-center justify-center group-hover:bg-blue-700 transition-colors">
                        <i data-feather="book-open" class="text-white" width="24" height="24"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Pinjaman</h3>
                        <p class="text-sm text-gray-500">Lihat buku dipinjam</p>
                    </div>
                </div>
            </a>

        </div>

        <!-- Daftar Buku -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                    <i data-feather="book" width="20" height="20" class="text-blue-600"></i>
                    Daftar Buku
                </h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Buku 1 - Contoh -->
                <?php
                while ($data = mysqli_fetch_assoc($qwr)):
                ?>
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all cursor-pointer group">
                        <div class="h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-t-xl flex items-center justify-center overflow-hidden">
                            <img src="uploads/<?= $data['sampul'] ?>" alt="Laskar Pelangi" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <h4 class="font-semibold text-sm truncate group-hover:text-blue-600 transition-colors" title="Laskar Pelangi">
                                <?= $data['judul'] ?>
                            </h4>
                            <p class="text-xs text-gray-500 truncate"><?= $data['penerbit'] ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= $data['tggl_terbit'] ?></p>
                            <p class="text-xs text-green-600 mt-1 font-medium">
                                Tersedia
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- NOTE: nanti 1 baris ini di-loop dengan PHP -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-blue-100 mt-8 py-4">
        <div class="container mx-auto px-4 text-center text-xs text-blue-400">
            © 2026 Perpustakaan SMKN 1 PRINGGABAYA
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>

</html>