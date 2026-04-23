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
