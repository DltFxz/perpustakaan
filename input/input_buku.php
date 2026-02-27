<?php
require '../config/koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['nama'] == null) {
    header('location:../index.php?pesan=login_dulu');
    exit();
}

if ($_SESSION['role'] != 'perpustakawan' && $_SESSION['role'] != 'admin') {
    session_destroy();
    header('location:../index.php?pesan=akses_ditolak');
    exit();
}

$id_buku = '';
$judul = '';
$penerbit = '';
$tanggal = '';
$sampul_lama = '';

if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];
    $sql = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id_buku'");
    $data = mysqli_fetch_assoc($sql);

    if ($data) {
        $judul = $data['judul'];
        $penerbit = $data['penerbit'];
        $tanggal = $data['tggl_terbit'];
        $sampul_lama = $data['sampul'];
    }
}

if (isset($_POST['simpan'])) {
    $id_buku = $_POST['id_buku'] ?? '';
    $judul = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $tggl_terbit = $_POST['tanggal'];
    
    if ($_FILES['sampul']['error'] == 0) {
        $nama_file = $_FILES['sampul']['name'];
        $tmp_file = $_FILES['sampul']['tmp_name'];

        // Ambil ekstensi
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        // Validasi tipe file (opsional)
        $tipe_diperbolehkan = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ekstensi, $tipe_diperbolehkan)) {
            echo "<script>alert('Tipe file harus JPG/PNG/GIF!');</script>";
            exit();
        }

        // Buat nama unik
        $nama_file_baru = date('YmdHis') . '_' . rand(1000, 9999) . '.' . $ekstensi;

        // Upload
        move_uploaded_file($tmp_file, "../uploads/" . $nama_file_baru);
        $sampul = $nama_file_baru;

        // Hapus file lama kalau perlu
        if (!empty($id_buku) && !empty($_POST['sampul_lama'])) {
            $file_lama = "../uploads/" . $_POST['sampul_lama'];
            if (file_exists($file_lama)) {
                unlink($file_lama);
            }
        }
    } else {
        $sampul = $_POST['sampul_lama'] ?? '';
    }

    if (empty($id_buku)) {

        $sql = "INSERT INTO buku (judul, penerbit, tggl_terbit, sampul) 
                VALUES ('$judul', '$penerbit', '$tggl_terbit', '$sampul')";
        $pesan = "Buku berhasil ditambahkan!";
    } else {

        $sql = "UPDATE buku SET 
                judul='$judul', 
                penerbit='$penerbit', 
                tggl_terbit='$tggl_terbit', 
                sampul='$sampul' 
                WHERE id_buku='$id_buku'";
        $pesan = "Buku berhasil diupdate!";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('$pesan');
            window.location.href = '../admin/perpustakawan.php';
        </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= empty($id_buku) ? 'Tambah' : 'Edit' ?> Buku - Perpustakaan SMKN 1 Pringgabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-blue-700 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i data-feather="book" class="text-white" width="20" height="20"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-white">
                            <?= empty($id_buku) ? 'Tambah Buku Baru' : 'Edit Buku' ?>
                        </span>
                        <p class="text-xs text-blue-200">SMKN 1 Pringgabaya</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="../admin/perpustakawan.php" class="p-2 hover:bg-white/20 rounded-lg transition-colors flex items-center gap-2">
                        <i data-feather="arrow-left" class="text-white" width="18" height="18"></i>
                        <span class="text-white text-sm hidden md:inline">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header Form -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <?= empty($id_buku) ? '➕ Tambah Buku' : '✏️ Edit Buku' ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= empty($id_buku) ? 'Isi data buku dengan lengkap' : 'Ubah data buku' ?>
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg max-w-2xl mx-auto">
            <div class="p-6">
                <form method="post" enctype="multipart/form-data">
                    <!-- HIDDEN FIELD UNTUK ID DAN SAMPUL LAMA -->
                    <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
                    <?php if (!empty($sampul_lama)): ?>
                        <input type="hidden" name="sampul_lama" value="<?= $sampul_lama ?>">
                    <?php endif; ?>

                    <!-- Judul Buku -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="judul">
                            Judul Buku <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-feather="book" class="text-gray-400" width="18" height="18"></i>
                            </div>
                            <input
                                type="text"
                                name="judul"
                                id="judul"
                                value="<?= htmlspecialchars($judul) ?>"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Masukkan judul buku"
                                required>
                        </div>
                    </div>

                    <!-- Penerbit -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="penerbit">
                            Penerbit <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-feather="home" class="text-gray-400" width="18" height="18"></i>
                            </div>
                            <input
                                type="text"
                                name="penerbit"
                                id="penerbit"
                                value="<?= htmlspecialchars($penerbit) ?>"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Masukkan nama penerbit"
                                required>
                        </div>
                    </div>

                    <!-- Tanggal Terbit -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal">
                            Tanggal Terbit <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-feather="calendar" class="text-gray-400" width="18" height="18"></i>
                            </div>
                            <input
                                type="date"
                                name="tanggal"
                                id="tanggal"
                                value="<?= $tanggal ?>"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-gray-700"
                                required>
                        </div>
                    </div>

                    <!-- Tampilkan Sampul Lama (KALAU EDIT) -->
                    <?php if (!empty($id_buku) && !empty($sampul_lama)): ?>
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampul Saat Ini</label>
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-24 bg-gray-100 rounded overflow-hidden border">
                                    <?php if (file_exists("../uploads/" . $sampul_lama)): ?>
                                        <img src="../uploads/<?= $sampul_lama ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-feather="image" class="text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-500">Kosongkan jika tidak ingin mengganti sampul</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Upload Sampul -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="sampul">
                            <?= empty($id_buku) ? 'Sampul Buku' : 'Ganti Sampul (opsional)' ?>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors cursor-pointer" id="uploadArea">
                            <input type="file" name="sampul" id="sampul" class="hidden" <?= empty($id_buku) ? 'required' : '' ?>>
                            <div class="flex flex-col items-center gap-2">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i data-feather="upload-cloud" class="text-blue-600" width="24" height="24"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">Klik untuk upload gambar sampul</p>
                                <p class="text-xs text-gray-400">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                                <p class="text-xs text-blue-600 mt-1 preview-text hidden">File terpilih: <span class="font-medium"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="../admin/perpustakawan.php" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i data-feather="x" width="18" height="18"></i>
                            Batal
                        </a>
                        <button type="submit" name="simpan" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-4 rounded-xl transition-all transform hover:scale-[1.02] shadow-md flex items-center justify-center gap-2">
                            <i data-feather="save" width="18" height="18"></i>
                            <?= empty($id_buku) ? 'Simpan Buku' : 'Update Buku' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Script untuk upload area
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('sampul');
        const previewText = document.querySelector('.preview-text');

        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                previewText.classList.remove('hidden');
                previewText.querySelector('span').textContent = fileName;
            }
        });

        // Drag & drop effect
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');

            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                const fileName = e.dataTransfer.files[0].name;
                previewText.classList.remove('hidden');
                previewText.querySelector('span').textContent = fileName;
            }
        });
    </script>
</body>

</html>