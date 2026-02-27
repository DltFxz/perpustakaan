<?php
require 'config/koneksi.php';

session_start();

if (isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $cek_admin = mysqli_query($conn, "SELECT * FROM admin WHERE nama = '$nama' AND password = '$password'");

    if ($cek_admin->num_rows > 0) {
        $data = $cek_admin->fetch_assoc();
        $_SESSION['login'] = true;
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'perpustakawan') {
            header('location:admin/perpustakawan.php');
        } else {
            header('location:admin/admin.php');
            exit();
        }
    }

    $cek_anggota = mysqli_query($conn, "SELECT * FROM anggota WHERE nama = '$nama' AND password = '$password'");

    if ($cek_anggota->num_rows > 0) {
        $data = $cek_anggota->fetch_assoc();
        $_SESSION['id_anggota'] = $data['id_anggota'];
        $_SESSION['login'] = true;
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header('location:main.php');
        exit();
    } else {
        echo "<script>
        alert('Username atau Password Salah');
        window.location.href = 'index.php';
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
    <title>Login Perpustakaan - DeepSeek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .font-lib {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-blue-50 flex items-center justify-center min-h-screen p-4 relative">
    <!-- Background Pattern - Buku dan Rak -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-10 left-10 text-8xl text-blue-800">📚</div>
        <div class="absolute bottom-10 right-10 text-8xl text-blue-800">📖</div>
        <div class="absolute top-20 right-20 text-6xl text-blue-800">✏️</div>
        <div class="absolute bottom-20 left-20 text-6xl text-blue-800">📕</div>
        <!-- Pola garis seperti rak buku -->
        <div class="absolute top-1/3 left-0 w-full h-1 bg-blue-200"></div>
        <div class="absolute top-2/3 left-0 w-full h-1 bg-blue-200"></div>
    </div>

    <!-- Kartu Perpustakaan -->
    <div class="bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(37,99,235,0.3)] w-full max-w-md overflow-hidden relative border border-blue-200">
        <!-- Stempel Perpustakaan -->
        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-blue-100 border-4 border-blue-200 flex items-center justify-center transform rotate-12 opacity-30">
            <span class="text-blue-400 font-bold text-xs text-center rotate-12">PERPUSTAKAAN<br>SEKOLAH</span>
        </div>

        <!-- Header dengan gradient biru -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center relative">
            <!-- Ilustrasi Buku Berjatuhan -->
            <div class="absolute inset-0 overflow-hidden opacity-10">
                <div class="absolute -left-4 top-2 text-6xl transform -rotate-12">📚</div>
                <div class="absolute -right-4 bottom-2 text-6xl transform rotate-12">📚</div>
            </div>

            <!-- Icon dengan Feather Icons -->
            <div class="flex justify-center items-center gap-3 mb-4">
                <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center transform -rotate-6 hover:rotate-0 transition-transform border border-white/30">
                    <i data-feather="book" class="text-white" width="24" height="24"></i>
                </div>
                <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center transform rotate-6 hover:rotate-0 transition-transform border border-white/30">
                    <i data-feather="pen-tool" class="text-white" width="24" height="24"></i>
                </div>
                <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center transform -rotate-3 hover:rotate-0 transition-transform border border-white/30">
                    <i data-feather="award" class="text-white" width="24" height="24"></i>
                </div>
            </div>

            <h2 class="font-lib text-3xl font-bold text-white">Login <br> Perpustakaan</h2>
            <p class="text-blue-100 text-sm mt-1 italic">"Jendela Ilmu Pengetahuan"</p>

            <!-- Nomor Anggota -->
            <div class="mt-3 inline-block bg-blue-800/50 px-4 py-1 rounded-full">
                <span class="text-blue-100 text-xs"><i data-feather="credit-card" class="inline mr-1" width="12" height="12"></i> Anggota Perpustakaan</span>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <!-- Info Perpustakaan dengan Feather Icons -->
            <div class="flex justify-between items-center mb-6 text-xs text-blue-600 bg-blue-50 p-3 rounded-xl border border-blue-200">
                <span><i data-feather="clock" class="inline mr-1" width="12" height="12"></i> 07.00 - 16.00</span>
                <span><i data-feather="calendar" class="inline mr-1" width="12" height="12"></i> 2024/2025</span>
                <span><i data-feather="book-open" class="inline mr-1" width="12" height="12"></i> 1.234 Buku</span>
            </div>

            <form class="space-y-5" method="post">
                <!-- Field Nama dengan Feather Icon -->
                <div>
                    <label class="block text-blue-800 text-sm font-semibold mb-2" for="nama">
                        <i data-feather="user" class="inline mr-2 text-blue-600" width="14" height="14"></i>
                        Nama Lengkap
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="user" class="text-blue-300 group-focus-within:text-blue-600 transition-colors" width="18" height="18"></i>
                        </div>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full pl-10 pr-4 py-3 border-2 border-blue-200 bg-blue-50/50 rounded-xl focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-200 transition-all placeholder-blue-300 text-blue-800"
                            required>
                        <!-- Hiasan pojok buku -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-2 border-r-2 border-blue-300 rounded-br-xl"></div>
                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-2 border-l-2 border-blue-300 rounded-tl-xl"></div>
                    </div>
                </div>

                <!-- Field Password dengan Feather Icon -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-blue-800 text-sm font-semibold" for="password">
                            <i data-feather="key" class="inline mr-2 text-blue-600" width="14" height="14"></i>
                            Password
                        </label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="lock" class="text-blue-300 group-focus-within:text-blue-600 transition-colors" width="18" height="18"></i>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            class="w-full pl-10 pr-10 py-3 border-2 border-blue-200 bg-blue-50/50 rounded-xl focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-200 transition-all placeholder-blue-300 text-blue-800"
                            required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                            <i data-feather="eye" class="text-blue-400 hover:text-blue-600 transition-colors" width="18" height="18"></i>
                        </button>
                        <!-- Hiasan pojok buku -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-2 border-r-2 border-blue-300 rounded-br-xl"></div>
                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-2 border-l-2 border-blue-300 rounded-tl-xl"></div>
                    </div>
                </div>

                <!-- Checkbox dengan Feather Icon -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-5 h-5 border-2 border-blue-300 rounded-md peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all group-hover:border-blue-400 bg-white"></div>
                            <i data-feather="check" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 transition-opacity" width="12" height="12"></i>
                        </div>
                        <span class="ml-2 text-sm text-blue-700 group-hover:text-blue-900">Ingat Saya Loh Yaa</span>
                    </label>
                </div>

                <!-- Tombol Login dengan Feather Icon -->
                <button
                    type="submit"
                    name="login"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2 group relative overflow-hidden">
                    <!-- Efek halaman buku -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i data-feather="book-open" width="18" height="18"></i>
                    <span>Masuk Perpustakaan</span>
                    <i data-feather="arrow-right" class="group-hover:translate-x-1 transition-transform" width="18" height="18"></i>
                </button>

                <!-- Link Daftar Anggota Baru dengan Feather Icon -->
                <div class="text-center pt-4 border-t border-blue-100">
                    <p class="text-sm text-blue-600">
                        Blumm punya akun??
                    </p>
                    <a href="registrasi.php" class="inline-flex items-center gap-2 text-blue-700 hover:text-blue-900 font-semibold mt-1 group">
                        <i data-feather="user-plus" class="group-hover:rotate-12 transition-transform" width="16" height="16"></i>
                        Daftar dulu boskuuuu
                        <i data-feather="arrow-right" class="group-hover:translate-x-1 transition-transform" width="12" height="12"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer dengan informasi perpustakaan dan Feather Icons -->
        <div class="bg-blue-50 px-8 py-3 border-t border-blue-200">
            <div class="flex justify-center gap-4 text-xs">
                <span class="text-blue-600"><i data-feather="home" class="inline mr-1" width="12" height="12"></i>SMKN 1 PRINGGABAYA</span>
                <span class="text-blue-300">|</span>
                <span class="text-blue-600"><i data-feather="clock" class="inline mr-1" width="12" height="12"></i> 7 Hari</span>
                <span class="text-blue-300">|</span>
                <span class="text-blue-600"><i data-feather="bell" class="inline mr-1" width="12" height="12"></i> Rp 500/hari</span>
            </div>
        </div>
    </div>

    <!-- Stiker Perpustakaan dengan Feather Icon -->
    <div class="fixed bottom-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs shadow-lg flex items-center gap-2">
        <i data-feather="bookmark" width="14" height="14"></i>
        <span>Perpustakaan SMKN 1 PRINGGABAYA</span>
    </div>

    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ganti icon eye
            this.innerHTML = type === 'password' ?
                '<i data-feather="eye" class="text-blue-400 hover:text-blue-600" width="18" height="18"></i>' :
                '<i data-feather="eye-off" class="text-blue-400 hover:text-blue-600" width="18" height="18"></i>';

            // Re-initialize feather untuk icon yang baru
            feather.replace();
        });
    </script>
</body>

</html>