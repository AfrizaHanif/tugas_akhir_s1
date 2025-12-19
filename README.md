# Rancang Bangun Aplikasi Penentuan Karyawan Terbaik Berbasis Web Menggunakan Metode Simple Additive Weighting (SAW) pada BPS Provinsi Jawa Timur

Aplikasi berbasis web yang dikembangkan untuk mendigitalisasi dan mengoptimalkan proses penilaian karyawan di **BPS Provinsi Jawa Timur**. Proyek ini merupakan implementasi dari Tugas Akhir saya yang berfokus pada efisiensi pengambilan keputusan menggunakan algoritma **Simple Additive Weighting (SAW)**.

---

## 🚀 Fitur Utama

-   **Implementasi Algoritma SAW:** Perhitungan otomatis nilai normalisasi dan peringkat akhir karyawan.
-   **Role-Based Authorization (RBAC):** Perbedaan hak akses antara Admin (Manajemen data & bobot), Kepala BPS Jawa Timur (Verifikasi), dan Pegawai (Melihat laporan).
-   **Dashboard Analytics:** Visualisasi data hasil penilaian.
-   **Optimasi Performa:** Memangkas waktu proses penentuan dari 4-5 minggu menjadi **01.79 menit**.

## 🛠️ Teknologi yang Digunakan

-   **Framework:** Laravel 10 (PHP)
-   **Frontend:** Bootstrap
-   **Database:** MySQL
-   **Tools:** Composer

## 📋 Prasyarat (Prerequisites)

Pastikan perangkat Anda sudah terinstall:

-   PHP >= 8.1
-   Composer
-   MySQL

## 🔧 Langkah Instalasi

1. **Clone Repositori**

    ```bash
    git clone [https://github.com/AfrizaHanif/tugas_akhir_s1.git](https://github.com/AfrizaHanif/tugas_akhir_s1.git)
    cd tugas_akhir_s1
    ```

2. **Install Dependencies**

    ```bash
    composer install
    ```

3. **Konfigurasi Environment**
   Salin file .env.example menjadi .env dan sesuaikan konfigurasi database Anda.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Migrasi Database & Seeding**
   Jalankan perintah berikut untuk membuat struktur tabel dan mengisi data awal (jika tersedia).

    ```bash
    php artisan migrate --seed
    ```

5. **Jalankan Aplikasi**

    ```bash
    php artisan serve
    ```

## 🖥️ Testing Aplikasi

### 🔑 Akun Demo

Untuk mencoba fitur hak akses, gunakan akun berikut:

-   Admin: testadmin | Password: bps3500
-   Kepala BPS Jawa Timur: testkbps | Password: bps3500
-   Pegawai: testpegawai | Password: bps3500

### 📂 Fitur Import Data

Aplikasi mendukung pengisian data massal menggunakan Excel untuk mempercepat proses penilaian.

-   **Sampel File:** Silakan gunakan [1A. Test Import Presensi Januari 2024.xlsx](./demo-data/1A. Test Import Presensi Januari 2024.xlsx), [1B. Test Import CKP Januari 2024.xlsx](./demo-data/1B. Test Import CKP Januari 2024.xlsx), [1C. Test Import SKP Januari 2024.xlsx](./demo-data/1C. Test Import SKP Januari 2024.xlsx) yang tersedia di folder `demo-data`.
-   **Cara Penggunaan:** Login sebagai Admin > Masuk ke menu Data Input > Klik Tombol Import > Upload file yang telah diunduh.

## 📝 Dokumentasi Akademik

Laporan lengkap mengenai riset dan perhitungan algoritma SAW pada proyek ini dapat diakses melalui [Repositori Kampus Universitas Dinamika](https://repository.dinamika.ac.id/id/eprint/7908/).

© 2025 Muhammad Afriza Hanif
