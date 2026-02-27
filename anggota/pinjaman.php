<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['id_anggota'])) {
    header('Location: ../index.php');
    exit();
}

$id_anggota = $_SESSION['id_anggota'];

$pinjaman = mysqli_query(
    $conn,
    "SELECT p.*, b.judul, b.sampul
     FROM peminjaman p
     JOIN buku b ON p.id_buku = b.id_buku
     WHERE p.id_anggota = '$id_anggota' 
     AND p.status = 'dipinjam'"
);

// $total_pinjaman = mysqli_query($conn, "SELECT COUNT(*) as total from peminjaman where id_anggota='$id_anggota'");
// $total = mysqli_fetch_assoc($total_pinjaman);

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

        /* Card styles */
        .stat-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
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

        /* Status badge */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fed7aa;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
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
    </style>
</head>

<body class="min-h-screen">

    <!-- Navbar Section - PINJAMAN ACTIVE -->
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
                    <a href="../main.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="../page/buku.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="book" width="18" height="18"></i>
                        <span class="text-sm font-medium">Buku</span>
                    </a>
                    <a href="pinjaman.php"
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
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
                            <p class="text-xs text-blue-200"><?= $_SESSION['nama'] ?></p>
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

                    <a href="../config/logout.php"
                        class="flex items-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-white px-3 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i data-feather="log-out" width="18" height="18"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden hidden mobile-menu pb-4" id="mobileMenu">
                <div class="flex flex-col space-y-2">
                    <a href="dashboard.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="../page/buku.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="book" width="18" height="18"></i>
                        <span class="text-sm font-medium">Buku</span>
                    </a>
                    <a href="pinjaman.php"
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="book-open" width="18" height="18"></i>
                        <span class="text-sm font-medium">Pinjaman</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-1 drop-shadow-lg">📋 Pinjaman</h1>
                <p class="text-neutral-500 text-sm font-light">Kelola data peminjaman dan pengembalian buku</p>
            </div>

            <!-- Tombol Tambah Pinjaman -->
            <a href="pinjam.php" class="mt-4 md:mt-0 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg font-medium">
                <i data-feather="plus" width="18" height="18"></i>
                <span>Tambah Pinjaman</span>
            </a>
        </div>

        <!-- Breadcrumb -->
        <!-- <div class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
            <a href="dashboard.php" class="hover:text-neutral-600 transition-colors">Dashboard</a>
            <i data-feather="chevron-right" width="14" height="14"></i>
            <span class="text-neutral-500 font-medium">Manajemen Pinjaman</span>
        </div> -->

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card bg-white rounded-xl p-5 border-l-4 border-blue-600 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Di Pinjam</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $aktif['total'] ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i data-feather="book-open" class="text-blue-600" width="20" height="20"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabel Daftar Pinjaman -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Sampul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">


                        <!-- Row 1 -->
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($pinjaman)):
                        ?>
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-600"><?= $no++ ?></td>
                                <td class="px-4 py-3">
                                    <img src="../uploads/<?= $data['sampul'] ?>"
                                        class="w-12 h-16 object-cover rounded-lg shadow-md border-2 border-gray-200">
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800"><?= $data['judul'] ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= $data['tanggal_pinjam'] ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= $data['tanggal_kembali'] ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                                        <?= $data['status'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="kembalikan.php?id=<?= $data['id_pinjam'] ?>" onclick="return confirm('Mau Kembalikan Buku Ini?')" class="flex item-center justify-between p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                            Kembalikan
                                            <i data-feather="bookmark" class="text-blue-600" width="20"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Konfirmasi Pengembalian (Hidden) -->
        <div class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="modal-content bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex items-center gap-3 text-green-600 mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i data-feather="check-circle" width="24" height="24"></i>
                        </div>
                        <h3 class="text-lg font-bold">Konfirmasi Pengembalian</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin buku <span class="font-semibold text-gray-800">"Laskar Pelangi"</span> telah dikembalikan oleh <span class="font-semibold text-gray-800">Budi Santoso</span>?</p>
                    <div class="flex gap-3">
                        <button class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">Batal</button>
                        <button class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 flex items-center justify-center gap-2 font-medium shadow-lg">
                            <i data-feather="check" width="16" height="16"></i>
                            Ya, Kembalikan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tagih Denda (Hidden) -->
        <div class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="modal-content bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex items-center gap-3 text-yellow-600 mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i data-feather="dollar-sign" width="24" height="24"></i>
                        </div>
                        <h3 class="text-lg font-bold">Tagih Denda</h3>
                    </div>
                    <p class="text-gray-600 mb-2">Buku: <span class="font-semibold text-gray-800">"Filosofi Teras"</span></p>
                    <p class="text-gray-600 mb-2">Peminjam: <span class="font-semibold text-gray-800">Ahmad Fauzi</span></p>
                    <p class="text-gray-600 mb-4">Terlambat: <span class="font-semibold text-red-600">3 hari</span></p>
                    <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Total Denda:</span>
                            <span class="text-2xl font-bold text-yellow-600">Rp 1.500</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Rp 500/hari x 3 hari keterlambatan</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">Nanti</button>
                        <button class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all duration-200 flex items-center justify-center gap-2 font-medium shadow-lg">
                            <i data-feather="check" width="16" height="16"></i>
                            Tagih Denda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-sm">
            © 2026 Perpustakaan SMKN 1 PRINGGABAYA | Panel Perpustakawan
        </div>
    </footer>

    <!-- Script untuk Feather Icons dan Mobile Menu -->
    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Toggle Mobile Menu
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>

</html>