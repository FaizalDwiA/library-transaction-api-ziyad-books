# Library Transaction API

RESTful API sederhana untuk sistem peminjaman buku perpustakaan  
dibangun sebagai technical challenge Ziyadbooks.

## 🚀 Tech Stack
- PHP 7.4 (Laravel)
- MySQL 8
- Docker & Docker Compose

---

## 📦 Fitur Utama
- Peminjaman buku dengan **database transaction (atomic)**
- Validasi stok buku dan kuota member
- Pencegahan race condition menggunakan `lockForUpdate`
- Custom error response dengan `ziyad_error_code` dan `trace_id`

---

## 🛠️ Cara Menjalankan Aplikasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd library-api
```

### 2. Jalankan Docker

```bash
docker-compose up -d
```

### 3. Install Dependency & Setup App

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

Aplikasi akan berjalan di:

```
http://localhost:8000
```

---

## 📌 Endpoint API

### POST /api/borrow

Digunakan untuk meminjam buku.

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

### ❌ Response Gagal (Contoh: Stok Habis)

```json
{
  "message": "Stok buku habis",
  "ziyad_error_code": "ZYD-ERR-001",
  "trace_id": "a1b2c3d4-xxxx"
}
```

---

## 🧪 Testing

Testing dilakukan menggunakan Postman dengan dua skenario:

1. Peminjaman berhasil
2. Peminjaman gagal karena stok habis

Detail pengujian ditunjukkan pada video demo.

---

## 🎥 Video Demo

📌 Link Video Demo:
**(isi link video di sini)**

Video menampilkan:

* Menjalankan aplikasi dengan `docker-compose up`
* Testing API sukses & gagal menggunakan Postman

---

## 📝 Catatan Teknis

* Transaction logic ditempatkan di service layer untuk menjaga controller tetap tipis
* Database transaction digunakan untuk menjamin konsistensi data
* Error response mengikuti kontrak khusus sesuai instruksi challenge