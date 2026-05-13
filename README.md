# Warkom - Aplikasi Toko Komputer

Warkom adalah sebuah aplikasi e-commerce toko komputer berbasis web yang dibangun menggunakan framework Laravel. Aplikasi ini menyediakan fitur bagi pengguna (user) untuk melihat produk, melakukan pemesanan, memberikan komentar (review), dan admin untuk mengelola data produk.

## Persyaratan Sistem

Pastikan sistem Anda telah memenuhi persyaratan berikut sebelum menjalankan aplikasi:
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Git

## Instalasi dan Menjalankan Aplikasi

Ikuti langkah-langkah di bawah ini untuk mengatur dan menjalankan aplikasi Warkom di mesin lokal Anda:

### 1. Clone Repository

Buka terminal atau command prompt Anda, lalu jalankan perintah berikut untuk mendownload kode (clone):

```bash
git clone (https://github.com/nabathnm/warkom)
cd warkom
```
*(Catatan: Ganti `<url-repository-anda>` dengan URL dari repository Git Anda jika sudah diunggah).*

### 2. Install Dependensi PHP (Composer)

Instal semua paket PHP yang dibutuhkan oleh Laravel menggunakan composer:

```bash
composer install
```

### 3. Install Dependensi Frontend (NPM)

Instal library frontend dan jalankan kompilasi asset (menggunakan Vite):

```bash
npm install
npm run build
```

### 4. Konfigurasi Environment (File `.env`)

Buat file konfigurasi environment dengan cara menyalin dari file contoh yang sudah ada:

```bash
cp .env.example .env
```
*(Jika Anda menggunakan Command Prompt Windows biasa, gunakan: `copy .env.example .env`)*

Buka file `.env` menggunakan teks editor Anda dan atur konfigurasi database. Pastikan Anda telah membuat database kosong di MySQL Anda (misalnya dengan nama `warkom_db`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warkom_db
DB_USERNAME=root
DB_PASSWORD=
```
*(Sesuaikan DB_DATABASE, DB_USERNAME, dan DB_PASSWORD dengan pengaturan MySQL Anda)*

### 5. Generate Application Key

Jalankan perintah ini untuk membuat kunci keamanan aplikasi (APP_KEY):

```bash
php artisan key:generate
```

### 6. Migrasi dan Seeding Database

Jalankan perintah migrasi untuk membuat tabel di database, sekaligus mengisi data awal (dummy products, users, admin, dan reviews) ke dalam database menggunakan seeder:

```bash
php artisan migrate:fresh --seed
```

### 7. Menjalankan Server Lokal

Terakhir, jalankan development server bawaan Laravel:

```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser kesayangan Anda pada alamat: `http://localhost:8000` atau `http://127.0.0.1:8000`

---

## Akun Demo / Test Akun

Setelah Anda menjalankan perintah seeding di langkah nomor 6, sistem otomatis membuatkan akun demo berikut yang bisa Anda gunakan untuk login:

### Akun Admin
- **Email:** `admin1@gmail.com`
- **Password:** `123456`
- **Role:** Mengelola produk

### Akun User Biasa
- **Email:** `andi@gmail.com`
- **Password:** `123456`
- **Role:** Melihat produk, belanja, dan memberikan review/komentar.

---

## Panduan Pengujian (Testing)

Aplikasi Warkom memiliki serangkaian pengujian otomatis untuk menjamin kualitas perangkat lunak. Pengujian ini terbagi menjadi dua, yaitu *Whitebox Testing* (menguji logika internal) dan *Blackbox Testing* (menguji fungsionalitas UI/alur pengguna).

### Persiapan Awal Khusus Blackbox Testing (Hanya Sekali)
Jika Anda menjalankan proyek ini di laptop baru (setelah melakukan `clone`), Anda harus mempersiapkan lingkungan Laravel Dusk terlebih dahulu:

1. **Install ChromeDriver**:
   Jalankan perintah berikut agar Dusk dapat mengontrol browser Chrome saat pengujian:
   ```bash
   php artisan dusk:chrome-driver
   ```

2. **Siapkan Environment Dusk**:
   Buat salinan file environment khusus untuk Dusk agar pengujian tidak menghapus data utama Anda:
   ```bash
   cp .env .env.dusk.local
   ```
   Buka file `.env.dusk.local`, lalu ubah pengaturan URL dan *database* menjadi SQLite:
   ```env
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=sqlite
   DB_DATABASE=database/dusk.sqlite
   ```

3. **Buat File Database Kosong**:
   Buat file database SQLite baru untuk digunakan oleh Dusk:
   ```bash
   touch database/dusk.sqlite
   ```
   *(Untuk pengguna Windows Command Prompt, gunakan perintah: `type nul > database\dusk.sqlite`)*

### 1. Whitebox Testing (PHPUnit)
Pengujian ini memverifikasi fungsionalitas logika di level aplikasi, seperti controller, model, dan interaksi dengan database.

Untuk menjalankan Whitebox Testing, jalankan perintah berikut di terminal:
```bash
php artisan test
```
Perintah ini akan menjalankan seluruh *Feature Test* dan *Unit Test* yang ada di dalam direktori `tests/Feature` dan `tests/Unit`.

### 2. Blackbox Testing (Laravel Dusk)
Pengujian ini dilakukan secara *End-to-End* (E2E) yang mensimulasikan interaksi nyata pengguna pada browser (seperti klik tombol, mengisi form, dan navigasi).

Untuk menjalankan Blackbox Testing, Anda memerlukan dua terminal:

**Langkah 1 (Terminal 1 - Menjalankan Server Testing):**
Laravel Dusk membutuhkan *web server* yang aktif. Karena pengujian akan me-reset *database*, sangat disarankan menggunakan environment khusus (`dusk.local`) agar tidak menghapus data pengembangan Anda.
```bash
php artisan serve --env=dusk.local
```

**Langkah 2 (Terminal 2 - Menjalankan Pengujian Dusk):**
Setelah server berjalan, buka tab/terminal baru dan jalankan:
```bash
php artisan dusk
```
Perintah ini akan membuka *browser headless* secara otomatis dan memvalidasi elemen antarmuka pengguna sesuai dengan skenario *test suite* yang berada di direktori `tests/Browser`. Hasil tangkapan layar (screenshot) untuk pengujian yang gagal akan disimpan otomatis di direktori `tests/Browser/screenshots/`.
