# Library Transaction API

RESTful API sederhana untuk sistem peminjaman buku perpustakaan dibangun sebagai technical challenge Ziyadbooks.

## 🚀 Tech Stack
- PHP 7.4 (Laravel)
- MySQL 8
- Docker & Docker Compose
- Composer (harus diinstall di local machine untuk setup Laravel)

---

## 📦 Fitur Utama
- Peminjaman buku dengan **database transaction (atomic)**
- Validasi **stok buku** dan **kuota member**
- Pencegahan race condition menggunakan `lockForUpdate`
- Custom error response dengan `ziyad_error_code` dan `trace_id`
- Logging transaksi berhasil dan gagal untuk tracking user

---

## 🛠️ Cara Menjalankan Aplikasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd library-transaction-api-ziyad-books
```

### 2. Jalankan Docker & Install Dependency & Setup App

```bash
cd src
composer install
cd ..
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

### Aplikasi Laravel akan berjalan di:

```
http://localhost:8000
```

### Phpmyadmin akan berjalan di:

```
http://localhost:8080
```

---

## 🔑 Membuat Token untuk User

### Buka Tinker:

```bash
docker-compose exec app php artisan tinker
```

### Buat token untuk user pertama:

```php
$user = App\Models\User::first();
$token = $user->createToken('postman-test')->plainTextToken;
echo $token;
```

Gunakan token ini di Postman/Insomnia pada header:

```
Authorization: Bearer <token>
```

---

## 📌 Endpoint API

### POST /api/borrow

Digunakan untuk meminjam buku.

#### Header:

```
Authorization: Bearer <token>
```

#### Request Body

```json
{
  "user_id": 1,
  "book_id": 1
}
```

---

### ✔️ Response Sukses

```json
{
  "message": "Borrow success"
}
```

---

### ❌ Response Gagal Stok Buku Habis

```json
{
  "message": "Stok buku habis",
  "ziyad_error_code": "ZYD-ERR-001",
  "trace_id": "a1b2c3d4-xxxx"
}
```

### ❌ Response Gagal Kuota Member Habis

```json
{
  "message": "Kuota peminjaman habis",
  "ziyad_error_code": "ZYD-ERR-002",
  "trace_id": "163ca1ba-ba9a-4b2d-9f35-7778c170f755"
}
```

---

## 🧪 Testing

Testing dilakukan menggunakan Postman dengan dua skenario:

1. Peminjaman berhasil
2. Peminjaman gagal karena stok habis atau kuota habis

Detail pengujian ditunjukkan pada video demo.

---

## 🎥 Video Demo

📌 Link Video Demo:
Link Video

Video menampilkan:

* Menjalankan aplikasi dengan `docker-compose up`
* Testing API sukses & gagal menggunakan Postman dengan token

---

## 📝 Catatan Teknis

* **Service Layer**: Transaction logic ditempatkan di service layer (`BorrowService`) untuk menjaga controller tetap tipis (Single Responsibility Principle)
* **Database Transaction**: Digunakan agar operasi peminjaman **atomic** (jika gagal di tengah, rollback otomatis)
* **Race Condition**: `lockForUpdate` digunakan untuk mencegah dua request meminjam buku yang sama secara bersamaan
* **Error Handling**: Custom error response dengan `ziyad_error_code` dan `trace_id` untuk memudahkan tracking
* **Logging**: Semua transaksi gagal dan berhasil dicatat di log Laravel (`storage/logs/laravel.log`) dengan trace_id untuk debugging
* **Validasi Input**: Menggunakan Form Request (`StoreBorrowTransactionRequest`) untuk memisahkan validasi dari controller
* **Authentication**: Semua endpoint API dilindungi token (Laravel Sanctum)

---