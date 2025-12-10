<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Rekam Medis - Praktek Mandiri Bidan Puji Susanti</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade {
            animation: fadeIn 1s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-animated {
            background-image: url('https://cdn-icons-png.flaticon.com/512/3774/3774299.png');
            background-size: 180px;
            background-repeat: no-repeat;
            background-position: right bottom;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 bg-animated">

    <!-- NAVBAR -->
    <header class="bg-white shadow">
        <div class="mx-auto flex justify-between items-center py-4 px-6 md:px-12 lg:px-20">

            <!-- Logo Resmi -->
            <div class="flex items-center gap-3">
                <img src="https://bidan-delima.id/logo.png" class="w-10 h-10 object-contain" />
                <h1 class="text-xl md:text-2xl font-bold text-amber-600 leading-tight">
                    Praktek Mandiri Bidan Puji Susanti
                </h1>
            </div>

            <nav>
                <a href="/admin/register"
                    class="flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition shadow-md hover:shadow-lg">
                    <img src="https://cdn-icons-png.flaticon.com/512/1828/1828479.png" class="w-5 h-5" />
                    Get Started
                </a>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 px-6 md:px-12 lg:px-20 py-20 fade">

        <!-- Kiri: Teks -->
        <div class="flex flex-col justify-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                Sistem Rekam Medis Digital Terintegrasi
            </h2>

            <p class="mt-4 text-lg text-gray-600">
                Solusi modern untuk mengelola data pasien, tenaga medis, dan pelayanan pendaftaran.
                Dirancang agar lebih cepat, akurat, dan mudah digunakan di lingkungan pelayanan tenaga medis.
            </p>

            <a href="/admin/register"
                class="mt-8 inline-block bg-amber-500 text-white px-6 py-3 rounded-xl text-lg font-semibold hover:bg-amber-600 transition shadow-lg hover:shadow-xl w-max">
                Mulai Sekarang
            </a>
        </div>

        <!-- Kanan: Gambar Dokter -->
        <div class="flex justify-center items-center">
            <img src="https://cdn-icons-png.flaticon.com/512/387/387561.png" class="w-80 md:w-[360px] drop-shadow-xl" />
        </div>
    </section>

    <!-- FITUR -->
    <section class="mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 px-6 md:px-12 lg:px-20">

        <!-- Total Pasien Dokter -->
        <a href="/total-pasien-dokter" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow hover:shadow-xl hover:scale-[1.02] transition-all fade flex gap-4">
                <img src="https://cdn-icons-png.flaticon.com/512/387/387561.png" class="w-12 h-12" />
                <div>
                    <h3 class="text-xl font-bold text-amber-600">Total Pasien Dokter</h3>
                    <p class="mt-2 text-gray-600">Lihat data keseluruhan jumlah pasien yang ditangani dokter.</p>
                </div>
            </div>
        </a>

        <!-- Total Pasien Bidan -->
        <a href="/total-pasien-bidan" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow hover:shadow-xl hover:scale-[1.02] transition-all fade flex gap-4">
                <img src="https://cdn-icons-png.flaticon.com/512/387/387558.png" class="w-12 h-12" />
                <div>
                    <h3 class="text-xl font-bold text-amber-600">Total Pasien Bidan</h3>
                    <p class="mt-2 text-gray-600">Akses informasi pasien yang ditangani oleh bidan.</p>
                </div>
            </div>
        </a>

        <!-- Dokter -->
        <a href="/total-dokter" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow hover:shadow-xl hover:scale-[1.02] transition-all fade flex gap-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3774/3774299.png" class="w-12 h-12" />
                <div>
                    <h3 class="text-xl font-bold text-amber-600">Dokter</h3>
                    <p class="mt-2 text-gray-600">Lihat daftar dokter yang bekerja di fasilitas ini.</p>
                </div>
            </div>
        </a>

        <!-- Bidan -->
        <a href="/total-bidan" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow hover:shadow-xl hover:scale-[1.02] transition-all fade flex gap-4">
                <img src="https://cdn-icons-png.flaticon.com/512/387/387561.png" class="w-12 h-12" />
                <div>
                    <h3 class="text-xl font-bold text-amber-600">Bidan</h3>
                    <p class="mt-2 text-gray-600">Informasi lengkap daftar bidan yang bertugas.</p>
                </div>
            </div>
        </a>

        <!-- Total Pendaftaran -->
        <a href="/total-pendaftaran" class="block">
            <div
                class="bg-white p-6 rounded-xl shadow hover:shadow-xl hover:scale-[1.02] transition-all fade flex gap-4">
                <img src="https://cdn-icons-png.flaticon.com/512/2972/2972149.png" class="w-12 h-12" />
                <div>
                    <h3 class="text-xl font-bold text-amber-600">Total Pendaftaran</h3>
                    <p class="mt-2 text-gray-600">Periksa jumlah total pendaftaran pasien.</p>
                </div>
            </div>
        </a>

    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-100 mt-20 py-6 text-center">
        <p class="text-gray-600">
            © 2025 Praktek Mandiri Bidan Puji Susanti — Sistem Rekam Medis Digital
        </p>
    </footer>

</body>

</html>
