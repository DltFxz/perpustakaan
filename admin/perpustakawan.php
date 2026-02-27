<?php
require '../config/koneksi.php';
session_start();

if ($_SESSION['nama'] == null && $_SESSION['role'] != 'perpustakawan') {
    header("location:../index.php");
    exit();
}

$buku = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'");
$status = mysqli_fetch_assoc($buku);

$qwr = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Perpustakawan - SMKN 1 PRINGGABAYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 0%, #3B82F6 100%);
        }

        /* Custom styles untuk mempercantik tampilan */
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

        .add-button {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }

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

        /* Cover image style - no hover effect */
        .book-cover {
            width: 3rem;
            height: 3.5rem;
            object-fit: cover;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="min-h-screen">
    <!-- Navbar dengan efek glassmorphism -->
    <nav class="bg-blue-700/90 backdrop-blur-md shadow-xl sticky top-0 z-50 border-b border-white/10">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Nama -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm border border-white/10">
                        <i data-feather="shield" class="text-white" width="20" height="20"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-white text-lg tracking-wide">Panel Perpustakawan</span>
                        <p class="text-xs text-blue-200 font-light">SMKN 1 PRINGGABAYA</p>
                    </div>
                </div>

                <!-- Profile & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/10">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-medium text-white"><?= $_SESSION['nama'] ?></p>
                            <p class="text-xs text-blue-200"><?= $_SESSION['role'] ?></p>
                        </div>
                        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center border-2 border-white/30">
                            <i data-feather="user" class="text-white" width="18" height="18"></i>
                        </div>
                    </div>
                    <a href="../config/logout.php" class="flex items-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-white px-3 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i data-feather="log-out" width="18" height="18"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan tombol tambah buku -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="text-white">
                <h2 class="text-3xl font-bold mb-1 drop-shadow-lg">📚 Manajemen Koleksi Buku</h2>
                <p class="text-blue-100 text-sm font-light">Kelola data buku perpustakaan SMKN 1 PRINGGABAYA</p>
            </div>
            <!-- Tombol Input Buku -->
            <a href="../input/input_buku.php" type="button" class="mt-4 md:mt-0 add-button text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg font-medium">
                <i data-feather="plus" width="18" height="18"></i>
                <span>Tambah Buku Baru</span>
            </a>
        </div>

        <!-- Statistik Total Buku -->
        <div class="flex justify-between">


            <div class="mb-8 flex">
                <div class="stat-card bg-white rounded-xl p-6 border-l-4 border-blue-600 max-w-xs shadow-xl">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i data-feather="book" class="text-blue-600" width="24" height="24"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Buku</p>
                            <p class="text-3xl font-bold text-gray-800">
                                <?php
                                $total_buku = totalData($conn, 'buku');
                                echo $total_buku;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <a class="cursor-pointer" href="peminjam.php">
                <div class="mb-8 flex">
                    <div class="stat-card bg-white rounded-xl p-6 border-l-4 border-blue-600 max-w-xs shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i data-feather="book" class="text-blue-600" width="24" height="24"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Buku Dipinjam</p>
                                <p class="text-3xl font-bold text-gray-800">
                                    <?= $status['total'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>






        <!-- Tabel Daftar Buku dengan Aksi -->
        <div class="table-container bg-white/95 backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">No</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">Sampul</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">Judul Buku</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">Penerbit</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">Tanggal Terbit</th>
                            <th class="px-4 py-4 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        while ($data = mysqli_fetch_assoc($qwr)):
                        ?>
                            <tr class="hover:bg-blue-50/80 transition-all duration-200">
                                <td class="px-4 py-4 text-gray-600 font-medium"><?= $no++ ?></td>
                                <td class="px-4 py-4">
                                    <div class="w-12 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center overflow-hidden shadow-md">
                                        <img src="../uploads/<?= $data['sampul'] ?>" alt="Sampul" class="book-cover">
                                    </div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-gray-800"><?= $data['judul'] ?></td>
                                <td class="px-4 py-4 text-gray-600"><?= $data['penerbit'] ?></td>
                                <td class="px-4 py-4 text-gray-600"><?= $data['tggl_terbit'] ?></td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="../input/input_buku.php?id_buku=<?= $data['id_buku'] ?>" class="action-button bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit Buku">
                                            <i data-feather="edit-2" width="16" height="16"></i>
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <form action="../config/delete.php?id_buku=<?= $data['id_buku'] ?>" method="post">
                                            <button name="hapus" class="action-button bg-red-100 hover:bg-red-200 text-red-600" title="Hapus Buku">
                                                <i data-feather="trash-2" width="16" height="16"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus (Hidden) -->
    <div class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="modal-content bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 text-red-600 mb-4">
                    <div class="bg-red-100 p-3 rounded-full">
                        <i data-feather="alert-triangle" width="24" height="24"></i>
                    </div>
                    <h3 class="text-lg font-bold">Hapus Buku</h3>
                </div>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus buku <span class="font-semibold text-gray-800">"Laskar Pelangi"</span>? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3">
                    <button class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">Batal</button>
                    <button class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center justify-center gap-2 font-medium shadow-lg">
                        <i data-feather="trash-2" width="16" height="16"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-sm text-white/80">
            © 2026 Perpustakaan SMKN 1 PRINGGABAYA | Panel Perpustakawan
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>

</html>