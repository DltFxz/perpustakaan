<?php
require '../config/koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['nama'] == null) {
    header("location:../index.php?pesan=login_dulu");
    exit();
}

// CEK ROLE - Admin atau Perpustakawan yang boleh akses
// $role_allowed = ['admin', 'perpustakawan'];
// if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $role_allowed)) {
//     header("location:../index.php?pesan=akses_ditolak");
//     exit();
// }


$qwr = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku desc");


$qwr_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku");
$total = mysqli_fetch_assoc($qwr_total);


if (isset($_GET['hapus'])) {
    $id_buku = mysqli_real_escape_string($conn, $_GET['hapus']);

    $query_gambar = mysqli_query($conn, "SELECT sampul FROM buku WHERE id_buku = '$id_buku'");
    $data_gambar = mysqli_fetch_assoc($query_gambar);

    if ($data_gambar && $data_gambar['sampul'] != '') {
        $path_gambar = "../uploads/" . $data_gambar['sampul'];
        if (file_exists($path_gambar)) {
            unlink($path_gambar); 
        }
    }

    $hapus = mysqli_query($conn, "DELETE FROM buku WHERE id_buku = '$id_buku'");

    if ($hapus) {
        echo "<script>
            alert('Buku berhasil dihapus!');
            window.location.href = 'buku.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Gagal menghapus buku: " . mysqli_error($conn) . "');
            window.location.href = 'buku.php';
        </script>";
        exit();
    }
}
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

                <!-- Menu Navigasi Desktop - BUKU YANG ACTIVE -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="../main.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="buku.php"
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i data-feather="book" width="18" height="18"></i>
                        <span class="text-sm font-medium">Buku</span>
                    </a>
                    <a href="../anggota/pinjaman.php"
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

                    <a href="../config/logout.php"
                        class="flex items-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-white px-3 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i data-feather="log-out" width="18" height="18"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu (Hidden by default) - BUKU YANG ACTIVE DI MOBILE -->
            <div class="md:hidden hidden mobile-menu pb-4" id="mobileMenu">
                <div class="flex flex-col space-y-2">
                    <a href="dashboard.php"
                        class="nav-menu flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
                        <i data-feather="home" width="18" height="18"></i>
                        <span class="text-sm font-medium">Home</span>
                    </a>
                    <a href="buku.php"
                        class="nav-menu active flex items-center gap-2 text-white px-4 py-3 rounded-lg transition-all duration-300">
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
    <div class="container mx-auto px-4 py-6">
        <!-- Header dengan tombol tambah buku -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📚 Manajemen Buku</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola koleksi buku perpustakaan SMKN 1 Pringgabaya</p>
            </div>

            <!-- TOMBOL TAMBAH BUKU (muncul untuk admin & perpustakawan) -->
            <?php
            if ($_SESSION['role'] == 'perpustakawan'):
            ?>
                <a href="../input/input_buku.php" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-md">
                    <i data-feather="plus" width="18" height="18"></i>
                    <span>Tambah Buku Baru</span>
                </a>
            <?php
            endif;
            ?>
        </div>

        <!-- Statistik Buku -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-600">
                <p class="text-xs text-gray-500">Total Buku</p>
                <p class="text-xl font-bold text-gray-800"><?= $total['total'] ?></p>
            </div>
        </div>

        <!-- Tabel Daftar Buku -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="bukuTable">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sampul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerbit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terbit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($qwr) > 0) {
                            while ($data = mysqli_fetch_assoc($qwr)):
                        ?>
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-600"><?= $no++ ?></td>
                                    <td class="px-4 py-3">
                                        <div class="w-12 h-14 bg-gray-100 rounded overflow-hidden border border-gray-200">
                                            <?php if ($data['sampul'] != '' && file_exists("../uploads/" . $data['sampul'])): ?>
                                                <img src="../uploads/<?= $data['sampul'] ?>" alt="sampul" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <i data-feather="book" class="text-white" width="20" height="20"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($data['judul']) ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($data['penerbit']) ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= date('d/m/Y', strtotime($data['tggl_terbit'])) ?></td>
                                </tr>
                            <?php
                            endwhile;
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <i data-feather="book-open" width="40" height="40" class="mx-auto text-gray-300 mb-2"></i>
                                    <p>Belum ada data buku</p>
                                    <a href="../input/input_buku.php" class="text-blue-600 hover:underline mt-2 inline-block">Tambah buku sekarang</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Fitur Search Sederhana
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#bukuTable tbody tr');

            tableRows.forEach(row => {
                let judul = row.cells[2]?.textContent.toLowerCase();
                let penerbit = row.cells[3]?.textContent.toLowerCase();

                if (judul.includes(searchValue) || penerbit.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>