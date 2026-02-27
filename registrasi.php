<?php
    require 'config/koneksi.php';

    if(isset($_POST['daftar'])){
        $nama = $_POST['nama'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $qwr = mysqli_query($conn, "INSERT INTO anggota(nama, password, role) VALUES('$nama','$password','$role')");
        if($qwr){
            header('location:index.php');
        }
        
        if($qwr){
            echo "<script>alert('Berasil Daftar')</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Perpustakaan - DeepSeek</title>
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
    </div>

    <!-- Kartu Registrasi Perpustakaan -->
    <div class="bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(37,99,235,0.3)] w-full max-w-md overflow-hidden relative border border-blue-200">
        <!-- Stempel Perpustakaan -->
        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-blue-100 border-4 border-blue-200 flex items-center justify-center transform rotate-12 opacity-30">
            <span class="text-blue-400 font-bold text-xs text-center rotate-12">PERPUSTAKAAN<br>SEKOLAH</span>
        </div>

        <!-- Header dengan gradient biru -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center relative">
            <div class="absolute inset-0 overflow-hidden opacity-10">
                <div class="absolute -left-4 top-2 text-6xl transform -rotate-12">📚</div>
                <div class="absolute -right-4 bottom-2 text-6xl transform rotate-12">📚</div>
            </div>

            <!-- Icon Registrasi -->
            <div class="bg-white/20 backdrop-blur-sm w-20 h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center transform hover:rotate-3 transition-transform border border-white/30">
                <i data-feather="user-plus" class="text-white" width="32" height="32"></i>
            </div>

            <h2 class="font-lib text-3xl font-bold text-white">Daftar Anggota</h2>
            <p class="text-blue-100 text-sm mt-1 italic">"Bergabung dengan perpustakaan sekolah"</p>

            <!-- Info Pendaftaran -->
            <div class="mt-3 inline-block bg-blue-800/50 px-4 py-1 rounded-full">
                <span class="text-blue-100 text-xs"><i data-feather="info" class="inline mr-1" width="12" height="12"></i> Pendaftaran Gratis!</span>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <form class="space-y-5" method="post">
                <!-- Field Nama Lengkap -->
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

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-blue-800 text-sm font-semibold mb-2">
                        <i data-feather="users" class="inline mr-2 text-blue-600" width="14" height="14"></i>
                        Daftar Sebagai
                    </label>
                    <div class="grid grid-cols-3 gap-3">    

                        <!-- Role Guru -->
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="role" value="Guru" class="sr-only peer">
                            <div class="border-2 border-blue-200 rounded-xl p-3 text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all hover:border-blue-400">
                                <i data-feather="award" class="mx-auto text-blue-400 peer-checked:text-blue-600 mb-1" width="24" height="24"></i>
                                <span class="block text-xs font-medium text-blue-700 peer-checked:text-blue-800">Guru</span>
                            </div>
                        </label>

                        <!-- Role Murid -->
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="role" value="Siswa" class="sr-only peer">
                            <div class="border-2 border-blue-200 rounded-xl p-3 text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all hover:border-blue-400">
                                <i data-feather="user" class="mx-auto text-blue-400 peer-checked:text-blue-600 mb-1" width="24" height="24"></i>
                                <span class="block text-xs font-medium text-blue-700 peer-checked:text-blue-800">Murid</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Field Password -->
                <div>
                    <label class="block text-blue-800 text-sm font-semibold mb-2" for="password">
                        <i data-feather="lock" class="inline mr-2 text-blue-600" width="14" height="14"></i>
                        Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="lock" class="text-blue-300 group-focus-within:text-blue-600 transition-colors" width="18" height="18"></i>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Buat password Anda"
                            class="w-full pl-10 pr-10 py-3 border-2 border-blue-200 bg-blue-50/50 rounded-xl focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-200 transition-all placeholder-blue-300 text-blue-800"
                            required>
                        <button name="daftar" type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                            <i data-feather="eye" class="text-blue-400 hover:text-blue-600 transition-colors" width="18" height="18"></i>
                        </button>
                        <!-- Hiasan pojok buku -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-2 border-r-2 border-blue-300 rounded-br-xl"></div>
                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-2 border-l-2 border-blue-300 rounded-tl-xl"></div>
                    </div>
                    <p class="text-xs text-blue-400 mt-1">Minimal 8 karakter</p>
                </div>

                <!-- Tombol Daftar -->
                <button
                    type="submit"
                    name="daftar"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2 group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i data-feather="user-plus" width="18" height="18"></i>
                    <span>Daftar Sekarang</span>
                    <i data-feather="arrow-right" class="group-hover:translate-x-1 transition-transform" width="16" height="16"></i>
                </button>

                <!-- Link ke Login -->
                <div class="text-center pt-2">
                    <p class="text-sm text-blue-600">
                        Udhh punya akunn??
                        <a href="index.php" class="text-blue-700 hover:text-blue-900 font-semibold inline-flex items-center gap-1 group">
                            Login disini boskuuu!
                            <i data-feather="log-in" class="group-hover:translate-x-1 transition-transform" width="14" height="14"></i>
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer sederhana -->
        <div class="bg-blue-50 px-8 py-3 border-t border-blue-200">
            <div class="flex justify-center gap-4 text-xs">
                <span class="text-blue-600"><i data-feather="home" class="inline mr-1" width="12" height="12"></i>SMKN 1 PRINGGABAYA</span>
                <span class="text-blue-300">|</span>
                <span class="text-blue-600"><i data-feather="clock" class="inline mr-1" width="12" height="12"></i> 07.00-16.00</span>
            </div>
        </div>
    </div>

    <!-- Stiker Perpustakaan -->
    <div class="fixed bottom-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs shadow-lg flex items-center gap-2">
        <i data-feather="book" width="14" height="14"></i>
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

            this.innerHTML = type === 'password' ?
                '<i data-feather="eye" class="text-blue-400 hover:text-blue-600" width="18" height="18"></i>' :
                '<i data-feather="eye-off" class="text-blue-400 hover:text-blue-600" width="18" height="18"></i>';

            feather.replace();
        });
    </script>
</body>

</html>