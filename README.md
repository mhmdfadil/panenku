# 🌾 PanenKu — Sistem Informasi Pencatatan Hasil Panen

Aplikasi web berbasis **CodeIgniter 4** untuk pencatatan dan manajemen hasil panen pertanian. Dirancang untuk petani agar dapat memantau produksi, menganalisis tren, dan mengelola data lahan secara efisien.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 📊 Dashboard | Ringkasan total panen, produksi, luas lahan, nilai panen |
| 🌱 Data Tanaman | Kelola jenis tanaman & komoditas |
| 🗺️ Data Lahan | Kelola data lahan pertanian |
| 📝 Pencatatan Panen | Form pencatatan dengan filtering & searching (AG Grid) |
| 📋 Riwayat Panen | Lihat semua riwayat dengan filter & search (AG Grid) |
| 📄 Laporan Panen | Ekspor laporan (print-friendly) |
| 📈 Grafik & Analisis | Visualisasi tren produksi per bulan/komoditas |
| 👤 Profil & Pengaturan | Edit profil, password, dan preferensi |
| 🌙 Dark/Light/System Mode | Tema otomatis mengikuti OS atau manual |
| 🔤 Mode Baca | Aksesibilitas: font lebih besar, kontras tinggi |

---

## 🛠️ Teknologi

- **Backend**: CodeIgniter 4 (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend Grid**: AG Grid Community (JavaScript)
- **Chart**: Chart.js
- **CSS Framework**: Bootstrap 5 + Custom CSS
- **Icons**: Bootstrap Icons
- **Pattern**: MVC (Model-View-Controller)

---

## 📋 Persyaratan Sistem

- PHP >= 8.1
- MySQL >= 8.0
- Composer >= 2.x
- Web Server: Apache/Nginx (atau `php spark serve` untuk development)
- Extension PHP: `intl`, `mbstring`, `mysqlnd`, `curl`, `json`

---

## 🚀 Instalasi & Setup

### 1. Clone / Extract Proyek

```bash
# Jika dari ZIP:
unzip panenku.zip -d panenku
cd panenku
```

### 2. Install Dependencies via Composer

```bash
composer install
```

> Jika belum ada Composer: https://getcomposer.org/download/

### 3. Konfigurasi Environment

```bash
cp env .env
```

Edit file `.env`:

```ini
# =============================
# APP CONFIGURATION
# =============================
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.appName = 'PanenKu'

# =============================
# DATABASE CONFIGURATION
# =============================
database.default.hostname = localhost
database.default.database = panenku_db
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 4. Buat Database

```sql
CREATE DATABASE panenku_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan Migrasi & Seeder

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

#### Skip, perintah ini untuk mengulang membuat tabel database.
```bash
php spark migrate:refresh
```

### 6. Atur Permissions

```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### 7. Jalankan Aplikasi

**Development (built-in server):**
```bash
php spark serve
```
Akses: http://localhost:8080

**Production (Apache):**

Pastikan `mod_rewrite` aktif dan arahkan DocumentRoot ke folder `public/`.

```apache
<VirtualHost *:80>
    ServerName panenku.local
    DocumentRoot /var/www/html/panenku/public
    <Directory /var/www/html/panenku/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 🔑 Akun Default (Setelah Seeder)

| Email | Password | Role |
|-------|----------|------|
| budi@panenku.id | password123 | Pengguna |
| sari@panenku.id | password123 | Pengguna |

---

## 📁 Struktur Direktori

```
panenku/
├── app/
│   ├── Config/          # Konfigurasi CI4 (Routes, Auth, dll)
│   ├── Controllers/     # Controller MVC
│   │   ├── Auth.php
│   │   ├── Dashboard.php
│   │   ├── Panen.php
│   │   ├── Riwayat.php
│   │   ├── Laporan.php
│   │   ├── Grafik.php
│   │   ├── Tanaman.php
│   │   ├── Lahan.php
│   │   └── Profil.php
│   ├── Models/          # Model database
│   │   ├── UserModel.php
│   │   ├── PanenModel.php
│   │   ├── TanamanModel.php
│   │   └── LahanModel.php
│   ├── Views/           # Tampilan (blade-like PHP)
│   │   ├── layouts/     # Layout utama
│   │   ├── auth/        # Login, register
│   │   ├── dashboard/
│   │   ├── panen/
│   │   ├── riwayat/
│   │   ├── laporan/
│   │   ├── grafik/
│   │   └── profil/
│   ├── Filters/         # Auth filter
│   └── Database/
│       ├── Migrations/  # Skema database
│       └── Seeds/       # Data awal
├── public/
│   ├── assets/
│   │   ├── css/         # Custom CSS + theme
│   │   └── js/          # Custom JS + AG Grid setup
│   └── index.php
├── writable/            # Cache, log, session
├── .env                 # Konfigurasi environment
├── composer.json
└── README.md
```

---

## 🎨 Tema & Aksesibilitas

- **Light Mode**: Tema terang default
- **Dark Mode**: Tema gelap, disimpan di `localStorage`
- **System Mode**: Mengikuti preferensi OS (`prefers-color-scheme`)
- **Mode Baca**: Font diperbesar (18px), spasi lebih lebar, kontras tinggi

Preferensi tersimpan di browser (`localStorage`) dan diterapkan instan tanpa reload.

---

## 📊 AG Grid

Semua tabel data menggunakan **AG Grid Community Edition**:
- Sorting multi-kolom
- Filtering per kolom
- Search global
- Pagination
- Export CSV/Excel (built-in)
- Responsive

---

## 🔒 Keamanan

- Password di-hash dengan `password_hash()` (bcrypt)
- CSRF protection aktif
- Session-based authentication
- Filter route untuk halaman terproteksi
- Validasi input server-side

---

## 📝 Lisensi

MIT License — Bebas digunakan untuk kebutuhan pribadi & komersial.

---

## 🤝 Kontribusi

Pull request dan issue sangat disambut. Pastikan mengikuti coding style CI4.

---

*PanenKu © 2024 — Catat Hasil Panen*
