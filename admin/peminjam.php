<?php
require '../config/koneksi.php';
session_start();

$query = mysqli_query($conn, "SELECT 
        p.*,
        b.judul,
        b.sampul,
        a.nama as nama_peminjam,
        a.role as roles
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN anggota a ON p.id_anggota = a.id_anggota
    WHERE status='dipinjam'
    ORDER BY p.tanggal_pinjam DESC 
");


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjam - SMKN 1 PRINGGABAYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 0%, #3B82F6 100%);
        }

        /* Navbar styles */
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

        .table-container {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
        }

        thead th {
            padding: 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
            letter-spacing: 0.05em;
        }

        tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:hover {
            background-color: #eff6ff;
        }

        tbody td {
            padding: 1rem;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .book-cover {
            width: 3rem;
            height: 4rem;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .badge-dipinjam {
            background-color: #378bffff;
            color: #ffffffff;
        }

        .badge-terlambat {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="min-h-screen">

    <!-- Navbar -->
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

                <!-- Profile & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/10">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-medium text-white"><?= $_SESSION['nama'] ?></p>
                            <p class="text-xs text-blue-200">Perpustakawan</p>
                        </div>
                        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center border-2 border-white/30">
                            <i data-feather="user" class="text-white" width="18" height="18"></i>
                        </div>
                    </div>

                    <!-- Tombol Menu Mobile -->
                    <button class="md:hidden p-2 hover:bg-white/20 rounded-lg transition-colors" id="menuToggle">
                        <i data-feather="menu" class="text-white" width="24" height="24"></i>
                    </button>

                    <a href="../config/logout.php" class="flex items-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-white px-3 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i data-feather="log-out" width="18" height="18"></i>
                    </a>
                </div>
            </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white drop-shadow-lg mb-2">📋 Data Peminjam Buku</h1>
                <p class="text-blue-100">Informasi anggota yang sedang meminjam buku</p>
            </div>
            <a href="perpustakawan.php" type="button" class="mt-4 md:mt-0 add-button text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg font-medium">
                <i data-feather="corner-down-right" width="18" height="18"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Tabel Peminjam -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sampul</th>
                        <th>Judul Buku</th>
                        <th>Peminjam</th>
                        <th>Jabatan</th>
                        <th>Tgl Pinjam</th>
                        <!-- <th>Tgl Kembali</th> -->
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 - Peminjam 1 -->
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)):
                    ?>
                        <tr>
                            <td class="font-medium text-blue-600"><?= $no++ ?></td>
                            <td>
                                <img src="../uploads/<?= $data['sampul'] ?>"
                                    class="book-cover" alt="Laskar Pelangi">
                            </td>
                            <td class="font-semibold text-gray-800"><?= $data['judul'] ?></td>
                            <td>
                                <div class="font-medium text-gray-800"><?= $data['nama_peminjam'] ?></div>
                            </td>
                            <td><?= $data['roles'] ?></td>
                            <td><?= $data['tanggal_pinjam'] ?></td>
                            <td>
                                <?php
                                if($data['status'] == 'dipinjam'):
                                ?>
                                <span class="badge badge-dipinjam"><?= $data['status'] ?></span>
                                <?php else: ?>
                                <span class="badge badge-dipinjam">Tersedia</span>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Detail Peminjam (Hidden) -->
        <div id="detailModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800">📋 Detail Peminjam</h3>
                    <button onclick="closeModal()" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-feather="x" class="text-gray-500" width="20" height="20"></i>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Peminjam</p>
                        <p class="font-semibold">Budi Santoso</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ID Anggota</p>
                        <p class="font-semibold">AGC-001</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kelas</p>
                        <p class="font-semibold">X IPA 1</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <p class="font-semibold">081234567890</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Judul Buku</p>
                        <p class="font-semibold">Laskar Pelangi</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                        <p class="font-semibold">10 Feb 2026</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            feather.replace();

            // Toggle Mobile Menu
            document.getElementById('menuToggle')?.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobileMenu');
                mobileMenu.classList.toggle('hidden');
            });

            function closeModal() {
                document.getElementById('detailModal').classList.add('hidden');
            }
        </script>
</body>

</html>